require "json"
require "net/http"

$API_KEY = 'AIzaSyDCobO4v0gsYoPKsodPJvgwuVLAi2rkM6A'
$API_KEY2 = 'AIzaSyC1tigod66HQ3SwbYYsCGKztPzFLeK8TPQ'
$triangleSideLength = 0.02
$imageSize = 0.01

$numRows = 9 #odd only
$numColumns = 19 #odd only

$strokeOpacity = 0.9
$strokeWeight = 3
$fillOpacity = 0.65
$colorCoefficient = 5 #increase for more redness, decrease for more greenness

triangleArray = Array.new($numRows) { Array.new($numColumns) { Hash.new } }
baselines = Array.new($numRows)

centerLat = ARGV[0].to_f;
centerLng = ARGV[1].to_f;

for x in (0..$numRows-1) do
  for y in (0..$numColumns-1) do
    triangleArray[x][y]['points'] = Array.new(3) {Hash.new}
    triangleArray[x][y]['center'] = Hash.new
    triangleArray[x][y]['direction'] = ((x+y)%2==0)? 'u' : 'd'
  end
end

triangleArray[$numRows/2][$numColumns/2]['center']['lat'] = centerLat
triangleArray[$numRows/2][$numColumns/2]['center']['lng'] = centerLng

def assignPoints(triangle) #triangle = triangleArray[$numRows/2][$numColumns/2]
  if triangle['direction'] == 'u'
    triangle['points'][0]['lng'] = triangle['center']['lng']
    triangle['points'][0]['lat'] = triangle['center']['lat'] + (Math.sqrt(3)/2)*$triangleSideLength*(2.0/3)
    triangle['points'][1]['lng'] = triangle['center']['lng'] - $triangleSideLength/2
    triangle['points'][2]['lng'] = triangle['center']['lng'] + $triangleSideLength/2
    triangle['points'][1]['lat'] = triangle['center']['lat'] - (Math.sqrt(3)/2)*$triangleSideLength*(1.0/3)
    triangle['points'][2]['lat'] = triangle['center']['lat'] - (Math.sqrt(3)/2)*$triangleSideLength*(1.0/3)
  elsif triangle['direction'] == 'd'
    triangle['points'][0]['lat'] = triangle['center']['lat'] + (Math.sqrt(3)/2)*$triangleSideLength*(1.0/3)
    triangle['points'][1]['lat'] = triangle['center']['lat'] + (Math.sqrt(3)/2)*$triangleSideLength*(1.0/3)
    triangle['points'][2]['lat'] = triangle['center']['lat'] - (Math.sqrt(3)/2)*$triangleSideLength*(2.0/3)
    triangle['points'][2]['lng'] = triangle['center']['lng']
    triangle['points'][0]['lng'] = triangle['center']['lng'] - $triangleSideLength/2
    triangle['points'][1]['lng'] = triangle['center']['lng'] + $triangleSideLength/2
  end
end

def putsTriangleObject(triangle, x, y)
  puts "triangleCoords#{x}#{y} = [{lat: #{triangle['points'][0]['lat']}, lng: #{triangle['points'][0]['lng']}}, " + "{lat: #{triangle['points'][1]['lat']}, lng: #{triangle['points'][1]['lng']}}, " + "{lat: #{triangle['points'][2]['lat']}, lng: #{triangle['points'][2]['lng']}}" + "];"
  puts "var triangleObj#{x}#{y} = new google.maps.Polygon({paths: triangleCoords#{x}#{y}, strokeColor: '#{triangle['color']}', strokeOpacity: #{$strokeOpacity.to_s}, strokeWeight: #{$strokeOpacity.to_s}, fillColor: '#{triangle['color']}', fillOpacity: #{$fillOpacity.to_s}});"
  puts "triangleObj#{x}#{y}.setMap(map); triangleObj#{x}#{y}.addListener('click', triangleObj#{x}#{y}Click);"
end

def putsTriangleClickFunction(triangle, x, y)
  puts "function triangleObj#{x}#{y}Click(event){ var content = 'Unevenness Level: #{triangle['elevation_score']}<br>Distance: #{((111.2*Math.sqrt(((x-$numRows)*(x-$numRows)) + ((y-$numColumns)*(y-$numColumns)))*$triangleSideLength) -28).abs } Km'; infoWindow.setContent(content); infoWindow.setPosition(event.latLng); infoWindow.open(map);}"
end

def getElevationScore(triangle, x, y)
  uri =  URI.parse("https://maps.googleapis.com/maps/api/elevation/json?locations=#{triangle['points'][0]['lat']},#{triangle['points'][0]['lng']}|#{triangle['points'][1]['lat']},#{triangle['points'][1]['lng']}|#{triangle['points'][2]['lat']},#{triangle['points'][2]['lng']}|#{triangle['center']['lat']},#{triangle['center']['lng']}")
  respjson = JSON.parse(Net::HTTP.get(uri))
  if(respjson['status'] == 'OK')
    triangle['points'][0]['elevation'] = (respjson['results'][0]['elevation']).to_f
    triangle['points'][1]['elevation'] = (respjson['results'][1]['elevation']).to_f
    triangle['points'][2]['elevation'] = (respjson['results'][2]['elevation']).to_f
    triangle['center']['elevation'] = (respjson['results'][3]['elevation']).to_f
    triangle['elevation_score'] = (((triangle['center']['elevation'])-(triangle['points'][0]['elevation'])).abs + ((triangle['center']['elevation'])-(triangle['points'][1]['elevation'])).abs + ((triangle['center']['elevation'])-(triangle['points'][2]['elevation'])).abs)/3
  else
    triangle['elevation_score'] = 0;
  end

  triangle['elevation_score'] += ((x - ($numColumns/2)).abs + (y - ($numRows/2)).abs)*2

  red = ''
  r = triangle['elevation_score']*$colorCoefficient.floor
  red = r.to_i.to_s(16)
  if red.length == 1
    red = '0' + red
  elsif red.length > 2
    red = 'FF'
  end
  green = ''
  g = (255 - (triangle['elevation_score']*$colorCoefficient)).floor.to_i
  if g<0
    g=0
  end
  green = g.to_s(16)
  if green.length == 1
    green = '0' + green
  end

  triangle['color'] = '#' + red + green + "00"
end

for x in (0..$numRows-1) do
  for y in (0..$numColumns-1) do
    triangleArray[x][y]['center']['lat'] = centerLat + (($triangleSideLength*Math.sqrt(3)/2) * (x-($numColumns/2)))
    triangleArray[x][y]['center']['lng'] = centerLng + ($triangleSideLength/2 * (y-($numRows/2)))
    if(triangleArray[x][y]['direction'] == 'u')
      triangleArray[x][y]['center']['lat'] -= ($triangleSideLength/(4.0* Math.sqrt(3)))
    else
      triangleArray[x][y]['center']['lat'] += ($triangleSideLength/(4.0* Math.sqrt(3)))
    end
    assignPoints(triangleArray[x][y])
  end
end

for x in (0..$numRows-1) do
  for y in (0..$numColumns-1) do
    getElevationScore(triangleArray[x][y], x, y)
    putsTriangleObject(triangleArray[x][y], x, y)
  end
end

puts "var imageBounds = {north: #{centerLat+$imageSize}, south: #{centerLat-$imageSize}, east: #{centerLng+$imageSize}, west: #{centerLng-$imageSize}};"
puts "planeOverlay = new google.maps.GroundOverlay('/spaceapps/img/plane.png',imageBounds); planeOverlay.setMap(map);"
puts "infoWindow = new google.maps.InfoWindow; }"


for x in (0..$numRows-1) do
  for y in (0..$numColumns-1) do
    putsTriangleClickFunction(triangleArray[x][y], x, y)
  end
end
