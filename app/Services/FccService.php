<?php

namespace App\Services;

use App\CashRegisterDetail;
use App\BusinessLocation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/**
 * Class FccService
 * @package App\Services
 */
class FccService
{
    public function fccIsInstalled(BusinessLocation $location)
    {
        if (file_exists(storage_path() . '/fcc' . '/' . $location->fcc_connector_id . '/.fccdata/installation.marker')) {
            if (file_get_contents(storage_path() . '/fcc' . '/' . $location->fcc_connector_id . '/.fccdata/installation.marker') == "DONE") {
                return true;
            }
            return false;
        }

        return false;
    }

    public function createFccConnector(BusinessLocation $location, $create_only)
    {

        if (!File::exists(storage_path() . '/fcc' . '/' . $location->fcc_connector_id)) {
            File::makeDirectory(storage_path() . '/fcc' . '/' . $location->fcc_connector_id);
        }

        File::copyDirectory(storage_path() . '/fcc_init', storage_path() . '/fcc' . '/' . $location->fcc_connector_id);

        if (!$create_only) {
            if (File::exists(storage_path() . '/fcc' . '/' . $location->fcc_connector_id)) {
                $this->initFccConnector($location);
                $this->startFccConnector($location);
            }
        }
    }

    public function initFccConnector($location)
    {
    	
        if (!$this->fccIsInstalled($location)) {
              //dd('&& sh init_fcc.sh --fcc_id ' . $location->fcc_connector_id . ' --fcc_secret ' . $location->fcc_password . ' --fcc_target_environment stable --fcc_server_port ' . $location->fcc_port);
              //shell_exec('cd ../storage/fcc/' . '&& mkdir aaa' );
           shell_exec( 'cd' .storage_path() . '/fcc' . '/' . $location->fcc_connector_id . ' && sh init_fcc.sh --fcc_id ' . $location->fcc_connector_id . ' --fcc_secret ' . $location->fcc_password . ' --fcc_target_environment stable --fcc_server_port ' . $location->fcc_port);
        }
    }

    public function startFccConnector(BusinessLocation $location)
    {
        // $output = exec('cd ../storage/fcc/' . $location->fcc_connector_id . ' && sh run_fcc.sh > /dev/null &');
        $output = shell_exec('cd ../storage/fcc/' . $location->fcc_connector_id . ' && sh run_fcc.sh > /dev/null &');
        // $output = shell_exec('cd ../storage/fcc/' . $location->fcc_connector_id . '&& ls -la');

        // dd($output);
    }

    public function fccIsAvailible(BusinessLocation $location)
    {
        
        $eas = CashRegisterDetail::where('id', 20)->first();
        $url = 'http://localhost:' . $location->fcc_port;
      
       
        if(!$location->fcc_port)
            return false;
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

      

        // Initialize cURL
        $curlInit = curl_init($url);
        
        // Set options
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        // Get response
        $response = curl_exec($curlInit);
        // dd($location->id.'<br>'.$response);

        // Close a cURL session
        curl_close($curlInit);
        
        return $response ? true : false;
    }

    public function authenticate(CashRegisterDetail $eas)
    {
        $location = BusinessLocation::where('id', $eas->location_id)->first();
        if (!$this->fccIsAvailible($location)) {
            $this->startFccConnector($location);
            sleep(30);
        }

        $reconect_tryed = 0;
        while (!$this->fccIsAvailible($location) && $reconect_tryed < 3) {
            $this->startFccConnector($location);
            sleep(30);
            $reconect_tryed++;
        }

        // $response = Http::acceptJson()->withBasicAuth($eas->eas_sn, $eas->location->eas_code)
        $response = Http::acceptJson()->withBasicAuth($eas->seriennumber, $location->eas_code)
            ->post('http://localhost:' . $location->fcc_port . '/oauth/token?grant_type=client_credentials');

        // dd($response->json());
        return $response;
    }

    public function terminateFccConnector(BusinessLocation $location)
    {
        // Define the port number you want to search for
        $portNumber = $location->fcc_port;

        // Execute the shell command to find the PID using lsof
        $command = "lsof -t -i:$portNumber";
        $output = shell_exec($command);

        // Check if the PID is found
        if (!empty($output)) {
            echo "Process running on port $portNumber has PID: $output. Killing the process...";
            // Kill the process using the found PID
            $killCommand = "kill $output";
            shell_exec($killCommand);
            echo "Process killed.";
        } else {
            echo "No process found running on port $portNumber";
        }
    }
}
