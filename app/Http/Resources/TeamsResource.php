<?php

namespace App\Http\Resources;

use App\Http\Resources\GeneratorResource as Generator;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\TeamsModel as Team;
use App\MembersModel as TeamMembers;
use App\UserLoginModel as Users;

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
            $slugFromName = Generator::generateSlugFromName($data['team_name']);
            $teamIdGenerate = Generator::generateRandomString(8);
            self::$response = self::_createTeam($data, $slugFromName, $teamIdGenerate);
        }
        return self::$response;
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
     * @param $slugFromName
     * @param $teamIdGenerate
     * @return array
     */
    private static function _createTeam($data, $slugFromName, $teamIdGenerate) {
        $create = Team::create([
            'uid' => $data['uid'],
            'owner' => $data['uid'],
            'team_id' => $teamIdGenerate,
            'team_name' => $data['team_name'],
            'team_slug' => $slugFromName,
            'sports_category' => $data['sports_category'],
            'active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $firebaseData = [
            'uid' => $data['uid'],
            'owner' => $data['uid'],
            'team_id' => $teamIdGenerate,
            'team_name' => $data['team_name'],
            'team_slug' => $slugFromName,
            'sports_category' => $data['sports_category'],
            'active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        FirebaseResource::teams($firebaseData);

        $members = TeamMembers::select('uid')->where(['team_id' => $teamIdGenerate], ['active' => 1])->get();

        if(count($members) > 0) {
            $membersInformation = self::_membersInformationByUID($members);
            self::$response = [
                'successCode' => 200,
                'successMessage' => 'Team found.',
                'team' => $create,
                'team_members' => $membersInformation
            ];
        } else {
            self::$response = [
                'successCode' => 200,
                'successMessage' => 'Team found.',
                'team' => $create,
                'team_members' => 'You have 0 members in your team'
            ];
        }

        return self::$response;
    }

    /**
     * @param $data
     * @return array
     */
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
            $slugFromName = Generator::generateSlugFromName($data['team_name']);
            self::$response = self::_editTeam($data, $slugFromName, $ownership);
        }
        return self::$response;
    }

    /**
     * @param $userID
     * @return mixed
     */
    private static function _findByOwnership($userID) {
        return Team::select('uid', 'active', 'team_id', 'created_at')
            ->where('uid', '=', $userID)->first();
    }

    /**
     * @param $data
     * @param $slugFromName
     * @param $ownership
     * @return array
     */
    private static function _editTeam($data, $slugFromName, $ownership) {
        $updateDetails = [
            'uid' => $ownership->uid,
            'owner' => $ownership->uid,
            'team_id' => $ownership->team_id,
            'team_name' => $data['team_name'],
            'team_slug' => $slugFromName,
            'sports_category' => $data['sports_category'],
            'active' => 1,
            'created_at' => $ownership->created_at,
            'updated_at' => Carbon::now()
        ];
        $update = Team::where(['uid' => $ownership->uid], ['team_id' => $ownership->team_id])
            ->update($updateDetails);

        if($update === 1) {
            FirebaseResource::teams($updateDetails);
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
            $teamMembers = TeamMembers::select('uid')
                ->where(['team_id' => $searchTeam->team_id],['active' => 1])->get();
            if(count($teamMembers) > 0) {
                $membersInformation = self::_membersInformationByUID($teamMembers);
                self::$response = [
                    'successCode' => 200,
                    'successMessage' => 'Team found.',
                    'team' => $searchTeam,
                    'team_members' => $membersInformation
                ];
            } else {
                self::$response = [
                    'successCode' => 200,
                    'successMessage' => 'Team found.',
                    'team' => $searchTeam,
                    'team_members' => 'You have 0 members in your team'
                ];
            }
        } else {
            self::$response = [
                'errorCode' => 504,
                'errorMessage' => 'Team not found, either create new team or join existing team.'
            ];
        }

        return self::$response;
    }

    /**
     * @param $teamMembers
     * @return mixed
     */
    protected static function _membersInformationByUID($teamMembers) {
        $uids = [];
        foreach ($teamMembers as $uid) {
            $uids[] = $uid->uid;
        }
        return Users::select('uid', 'avatar_path', 'first_name', 'last_name')->whereIn('uid', $uids)->get();
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

    public static function joinSelectedTeam(array $data) {
        $join = new TeamMembers();
        $join->uid = $data['uid'];
        $join->team_id = $data['team_id'];
        $join->created_at = Carbon::now();
        $join->updated_at = Carbon::now();
        $join->save();
    }

}
