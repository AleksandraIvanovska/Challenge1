<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Jobs;
use App\Locations;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use SimpleXMLElement;

class ChallengeController extends Controller
{
    public function index()
    {
        $path = storage_path() . '/challege.json';
        $json=json_decode(file_get_contents($path),true);
        foreach ($json as $item) {
            $this->createCandidate($item);
            $this->createLocationsAndAttachCandidates($item);
            $this->createJobsAndAttachCandidates($item);


            $job_title = Jobs::pluck('title');
            $description = Jobs::where('title', 'Software engineer')->first();
        }
    }

    public function createCandidate ($item)
    {
        Candidate::create([
            'name' => isset($item['name']) ?  $item['name'] : null,
            'birth_date' => isset($item['birth_date']) ? $item['birth_date'] : null,
            'skills' => isset($item['skills']) ? json_encode($item['skills']) : null
        ]);
    }

    public function createLocationsAndAttachCandidates($item)
    {
        $data = $item['locations'];
        $locations = explode(",", $data);
        $candidate = Candidate::where('name', $item['name'])->first();
        foreach ($locations as $location) {
            $row = explode(".", $location);
            if ("No thanks" !== $row[0] && "-" !== $row[0]) {
                if (!Locations::where('city', $row[0])->where('country', $row[1])->exists() && isset($row[0]) && isset($row[1])) {
                    $candidate_location = Locations::create([
                        'city' => isset($row[0]) ? $row[0] : null,
                        'country' => isset($row[1]) ? $row[1] : null
                    ]);
                    $candidate->locations()->attach($candidate_location['id']);
                }
            }
        }
    }

    public function createJobsAndAttachCandidates($item)
    {
        $jobs = $item['jobs'];
        $candidate = Candidate::where('name', $item['name'])->first();
        foreach ($jobs as $job) {
            $decoded_job = base64_decode($job);
            if ($decoded_job != 'You thought everything would be xml ? Did this break your code :D ?') {
                $xml = simplexml_load_string($decoded_job);
                $json = json_encode($xml);
                print_r($json);
                $new_job = Jobs::create([
                    'title' => $json['title'],
                    'started_at' => isset($json['started_at']) ? $json['started_at'] : null,
                    'finished_at' => isset($json['finished_at']) ? $json['finished_at'] : null,
                    'description' => isset($json['description']) ? $json['description'] : null
                ]);
                $candidate->locations()->attach($new_job['id']);
            }
        }
    }


}
