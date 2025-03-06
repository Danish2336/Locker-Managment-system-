<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LockerModel;

class Home extends BaseController
{
    public function index(): string
    {
        $lockerModel = new LockerModel();
        $lockers = $lockerModel->findAll();

        return view('welcome_message', ['lockers' => $lockers]);
    }


    public function issueLocker()
    {
        $userId = $this->request->getPost('user_id');

        // Load models
        $userModel = new UserModel();
        $lockerModel = new LockerModel();

        // Fetch all lockers
        $lockers = $lockerModel->findAll();

        // Check if user exists
        $user = $userModel->find($userId);

        if (!$user) {
            return view('welcome_message', ['error' => 'User ID not found.', 'lockers' => $lockers]);
        }

        // Check if user already has a locker assigned
        $existingLocker = $lockerModel->select('locker_id, locker_no')
            ->where('user_id', $userId)
            ->where('status', 'occupied')
            ->first();

        if ($existingLocker) {
            return view('welcome_message', [
                'error' => 'User already has a locker assigned (Locker No: ' . $existingLocker['locker_no'] . ').',
                'lockers' => $lockers
            ]);
        }

        $nextLocker = $lockerModel
            ->where('status', 'free')
            ->where('next_locker_no', 1)
            ->first();

        // If no locker is flagged with next_locker_no = 1, find the first free locker
        if (!$nextLocker) {
            $nextLocker = $lockerModel
                ->where('status', 'free')
                ->orderBy('locker_no', 'asc')
                ->first();
        }

        // Proceed to issue the locker if found
        if ($nextLocker) {
            // Issue the locker to the user
            $lockerModel->update($nextLocker['locker_id'], [
                'status' => 'occupied',
                'user_id' => $userId,
                'next_locker_no' => 1
            ]);

            // Find the next free locker to flag as next available
            $newNextLocker = $lockerModel
                ->where('status', 'free')
                ->where('locker_no >', $nextLocker['locker_no'])
                ->orderBy('locker_no', 'asc')
                ->first();

            // Update the next_locker_no flag for the next free locker, if found
            if ($newNextLocker) {
                $lockerModel->update($newNextLocker['locker_id'], ['next_locker_no' => 1]);
            }

            // Reset the next_locker_no flag for all other lockers
            $lockerModel->set('next_locker_no', 0)
                ->where('locker_id !=', $newNextLocker['locker_id'] ?? $nextLocker['locker_id'])
                ->update();

            $lockers = $lockerModel->findAll();
            return view('welcome_message', [
                'success' => 'Locker ' . $nextLocker['locker_no'] . ' issued successfully to User ID: ' . $userId,
                'lockers' => $lockers
            ]);
        }

        // If no free locker is available
        return view('welcome_message', ['error' => 'No available lockers.', 'lockers' => $lockers]);
    }




    public function releaseLocker()
    {
        $userId = $this->request->getPost('user_id');

        // Load models
        $userModel = new UserModel();
        $lockerModel = new LockerModel();

        // Fetch all lockers
        $lockers = $lockerModel->findAll();

        // Check if user exists
        $user = $userModel->find($userId);

        if (!$user) {
            return view('welcome_message', ['error' => 'User ID not found.', 'lockers' => $lockers]);
        }

        $availableLocker = $lockerModel->where('user_id', $userId);

        // relese the locker
        $lockerModel->update($availableLocker->locker_id, [
            'status' => 'free',
            'user_id' => null,
            'next_locker_no' => 0
        ]);

        return view('welcome_message', ['success' => 'Locker free successfully', 'lockers' => $lockers]);
    }
}
