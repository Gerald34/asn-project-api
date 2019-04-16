<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\TeamsModel as Team;

/**
 * Class TeamsResource
 * @package App\Http\Resources
 */
class TeamsResource extends JsonResource
{
    private static $response;

    /**
     * @param array $data
     * @return array|\Illuminate\Database\Query\Builder
     */
    public static function dataCollection(array $data) {
        $ownership = self::_validateOwnership($data['uid']);
        if (isset($ownership) && $ownership->active === 1) {
            self::$response = [
                'errorCode' => 501,
                'errorMessage' => 'Sorry you cannot create more than 1 team.'
            ];
        } else {
            $slugGenerator = strtolower(self::_generateSlugFromName($data['team_name']));
            $teamIdGenerate = self::_generateRandomTeamID(8);
            self::$response = self::_createTeam($data, $slugGenerator, $teamIdGenerate);
        }
        return self::$response;
    }

    /**
     * @param string $name
     * @return mixed
     */
    private static function _generateSlugFromName(string $name) {
        return str_replace(' ', '_', $name);
    }

    /**
     * @param $length
     * @return string
     */
    private static function _generateRandomTeamID($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtolower($randomString);
    }

    /**
     * @param $userID
     * @return mixed
     */
    private static function _validateOwnership($userID) {
         return Team::select('uid', 'active')->where('uid',$userID)->first();
    }

    /**
     * @param $data
     * @param $slugGenerator
     * @param $teamIdGenerate
     * @return \Illuminate\Database\Query\Builder
     */
    private static function _createTeam($data, $slugGenerator, $teamIdGenerate) {
        $create = Team::create([
            'uid' => $data['uid'],
            'owner' => $data['uid'],
            'team_id' => $teamIdGenerate,
            'team_name' => $data['team_name'],
            'team_slug' => $slugGenerator,
            'sports_category' => $data['sports_category'],
            'active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return $create;
    }

    public static function editByOwner($data) {
        $ownership = self::_findByOwnership($data['uid']);
        if ($ownership->uid !== $data['uid'] && $ownership->active !== 1) {
            self::$response = [
                'errorCode' => 502,
                'errorMessage' => 'Could not edit team information: Access denied.'
            ];
        } elseif(!isset($ownership)) {
            self::$response = [
                'errorCode' => 503,
                'errorMessage' => 'Team not found.'
            ];
        } else {
            $slugGenerator = strtolower(self::_generateSlugFromName($data['team_name']));
            self::$response = self::_editTeam($data, $slugGenerator, $ownership);
        }
        return self::$response;
    }

    private static function _findByOwnership($userID) {
        return Team::select('uid', 'active', 'team_id', 'created_at')
            ->where('uid', '=', $userID)
            ->first();
    }

    private static function _editTeam($data, $slugGenerator, $ownership) {
        $updateDetails = [
            'uid' => $ownership->uid,
            'owner' => $ownership->uid,
            'team_id' => $ownership->team_id,
            'team_name' => $data['team_name'],
            'team_slug' => $slugGenerator,
            'sports_category' => $data['sports_category'],
            'active' => 1,
            'created_at' => $ownership->created_at,
            'updated_at' => Carbon::now()
        ];
        $update = Team::where(['uid' => $ownership->uid], ['team_id' => $ownership->team_id])
            ->update($updateDetails);

        if($update === 1) {
            self::$response = [
                'successCode' => 202,
                'successMessage' => $ownership->team_name . ' is successfully updated.'
            ];
        } else {
            self::$response = [
                'errorCode' => 509,
                'errorMessage' => $ownership->team_name . ' could not be updated: No new information to update.'
            ];
        }

        return self::$response;
    }

    /**
     * @param $uid
     * @return array
     */
    public static function findTeamByUID($uid) {
        $searchTeam = Team::where('uid', $uid)->first();

        if(isset($searchTeam)) {
            self::$response = [
                'successCode' => 200,
                'successMessage' => 'Team found.',
                'team' => $searchTeam
            ];
        } else {
            self::$response = [
                'errorCode' => 504,
                'errorMessage' => 'Team not found, either create new team or join existing team.'
            ];
        }

        return self::$response;
    }

    /**
     * @param $uid
     * @param $teamHash
     * @return array
     */
    public static function findAndRemoveTeamByOwnership($uid, $teamHash) {
        $ownership = Team::where('team_id', $teamHash)->first();
        if(isset($ownership)) {
            if ($ownership->uid === $uid) {
                // Delete team row
                Team::where('team_id', $teamHash)->delete();
                self::$response = [
                    'successCode' => 206,
                    'successMessage' => 'Team data successfully removed.'
                ];
            } else {
                // Ownership access
                self::$response = [
                    'errorCode' => 505,
                    'errorMessage' => 'Ownership access denied.'
                ];
            }
        } else {
            self::$response = [
                'errorCode' => 506,
                'errorMessage' => 'Team not found.'
            ];
        }

        return self::$response;
    }

}
