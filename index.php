<?php

// include simpleDOM library
foreach (glob("include/*.php") as $filename)
{
    include $filename;
}

// open the csv file
$csvImport = fopen("competitors.csv", "r") or die("Unable to open file!");
$csvExport = fopen("competitors_matched.csv", "w") or die("Error creating CSV!");
$row = 0;
$competitorsUrls = array();
// competitor names go here
$competitors = array("Marketo", "Hubspot", "Pardot", "Eloqua");

// loop through the CSV rows
while (($data = fgetcsv($csvImport, 0, ",")) !== FALSE) {
  if($row == 0){ $row++; continue; }
  // $competitorsNames[$data[0]] = array();
  $competitorsUrls[$data[1]] = array();
};

foreach ($competitorsUrls as $competitorsUrl => $competitorsFound) {
  $site_url = file_get_html($competitorsUrl);
  // finds all matches present in the source code of the URLs provided
  foreach ($competitors as $competitor) {
    $pattern = '/'.$competitor.'/i';

    if(preg_match($pattern, $site_url, $matches)){
      foreach($matches as $match){
        array_push($competitorsFound, $match);
      }
    }
  }
  $competitorsUrls[$competitorsUrl] = $competitorsFound;
}

fclose($csvImport);
$fp = fopen('competitors_matched.csv', 'w');
fputcsv($fp, array("company_url", "match 1", "match 2", "match 3"));
foreach ($competitorsUrls as $competitorsUrl => $competitorsFound)
  {
    $line = array();
    array_push($line, $competitorsUrl);
    $line = array_merge($line, $competitorsFound);
    fputcsv($fp, $line);
  }

  fclose($fp);
?>
