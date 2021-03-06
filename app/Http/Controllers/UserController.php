<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyInformationModel as UserProfile;
use Carbon\Carbon;
class UserController extends Controller
{
    private $response;

    public function getMyInformation($userID)
    {
        $userProfile = UserProfile::where('user_id', $userID)->first();

        $this->response = [
            'successCode' => 200,
            'successMessage' => 'Profile Found',
            'userInformation' => $userProfile
        ];

        return $this->response;
    }

    public function updateProfile(Request $request)
    {
        $uid = $request->input('uid');

        $userInformation = [
            'biography' => $request->input('biography'),
            'current_city' => $request->input('current_city'),
            'work_place' => $request->input('workplace'),
            'education' => $request->input('education'),
            'home_town' => $request->input('home_town'),
            'date_of_birth' => $request->input('dateOfBirth'),
            'updated_at' => Carbon::now()
        ];

        if (empty($userInformation['biography'])) {
            $userInformation['biography'] = 'Edit/Add Biography';
        }

        if (empty($userInformation['current_city'])) {
            $userInformation['current_city'] = 'Edit/Add Current City';
        }

        if (empty($userInformation['workplace'])) {
            $userInformation['work_place'] = 'Edit/Add Workplace';
        }

        if (empty($userInformation['education'])) {
            $userInformation['education'] = 'Edit/Add Education';
        }

        if (empty($userInformation['home_town'])) {
            $userInformation['home_town'] = 'Edit/add Home Town';
        }

        if (empty($userInformation['date_of_birth'])) {
            $userInformation['date_of_birth'] = 'Edit/add Date Of Birth';
        }

        $update = UserProfile::where('user_id', $uid)->update($userInformation);

        if ($update === 1) {
            $this->response = [
                'successCode' => 204,
                'successMessage' => 'Profile successfully updated',
                'userInformation' => UserProfile::where('user_id', $uid)->first()
            ];
        } else {
            $this->response = [
                'errorCode' => 204,
                'errorMessage' => 'Profile count not update',
                'userInformation' => UserProfile::where('user_id', $uid)->first()
            ];
        }

        return $this->response;
    }
}
