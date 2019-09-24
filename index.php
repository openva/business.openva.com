<?php

include('vendor/autoload.php');

function get_content($url)
{

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $string = curl_exec($ch);
    curl_close($ch);

    if (empty($string))
    {
        return FALSE;
    }

    return $string;

}

$template = new Smarty;

$page_title = 'Virginia Businesses';
$browser_title = 'Virginia Businesses';
$page_body = '
		<article>

			<form method="get" action="/search.php">
				<label for="query">Search</label>
				<input type="text" size="50" name="query" id="query">
				<input type="submit" value="Go">
			</form>

		</article>';



		/*
		* Query our API for recent businesses
		*/
		if (!empty($SERVER['HTTPS']))
		{
			$api_url = 'https';
		}
		else {
			$api_url = 'http';
		}
		$api_url .= '://';
		$api_url .= $_SERVER['SERVER_NAME'];
		$api_url .= '/api/recent';

		$recent_json = get_content($api_url);
		$recent = json_decode($recent_json);
		if ($recent != FALSE)
		{
			
			$page_body .= '
				<article class="container">
				<h2>Newest Businesses</h2>';

			$i=3;
			if (count($recent) > 9)
			{
				$recent = array_slice($recent, 0, 9);
			}
			foreach ($recent as $business)
			{

				if ( ($i % 3) == 0 )
				{
					$page_body .= '<div class="row">';
				}
				
				$page_body .= '
					<div class="card small">
						<h3><a href="/business/' . $business->EntityID . '">' . $business->Name . '</a></h3>
						<p>';
				if (!empty($business->City))
				{
					$page_body .= $business->City . ', ' . $business->State . '<br>';
				} 
				$page_body .= date('M d, Y', strtotime($business->IncorpDate)) . '</p>
					</div>';

				if ( ($i % 3) == 2 )
				{
					$page_body .= '</div>';
				}
				$i++;

			}

			$page_body .= '</ul></article>';
		
		}

$page_body .= '
		<article>

		<table>
			<caption>Download Business Data</caption>
			<thead>
				<tr>
					<th scope="col">File</th>
					<th scope="col">Size</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><a href="data/amendment.csv">Entity Amendments</a></td>
					<td>6 MB</td>
				</tr>
				<tr>
					<td><a href="data/corp.csv">Corporate Entities</a></td>
					<td>87 MB</td>
				</tr>
				<tr>
					<td><a href="data/llc.csv">LLC Entities</a></td>
					<td>156 MB</td>
				</tr>
				<tr>
					<td><a href="data/lp.csv">LP Entities</a></td>
					<td>3 MB</td>
				</tr>
				<tr>
					<td><a href="data/merger.csv">Entity Mergers</a></td>
					<td>3 MB</td>
				</tr>
				<tr>
					<td><a href="data/name.history.csv">Entity Name/Fictitious Name History</a></td>
					<td>16 MB</td>
				</tr>
				<tr>
					<td><a href="data/officer.csv">Entity Officers/Directors</a></td>
					<td>29 MB</td>
				</tr>
				<tr>
					<td><a href="data/reserved.name.csv">Entity Reserved Names</a></td>
					<td>0.1 MB</td>
				</tr>
				<tr>
					<td><a href="data/tables.csv">Descriptive Tables</a></td>
					<td>0.1 MB</td>
				</tr>
				<tr>
					<td><a href="http://scc.virginia.gov/clk/data/CISbemon.CSV.zip">All Data, CSV</a></td>
					<td>77 MB</td>
				</tr>
				<tr>
					<td><a href="data/vabusinesses.sqlite">All Data, SQLite</a></td>
					<td>321 MB</td>
				</tr>
			</tbody>
		</table>
		</article>';

$template->assign('page_body', $page_body);
$template->assign('page_title', $page_title);
$template->assign('browser_title', $browser_title);

$template->display('includes/templates/simple.tpl');
