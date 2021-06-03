<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class UserService
{

    public function getUsers() : array
    {
        $file = fopen(storage_path("app/users.txt"), "r");

        $registers = [];

        while(!feof($file)) {
            array_push($registers, fgets($file));
        }

        $registers = str_replace("\r\n", "", $registers);
        unset($registers[0]);

        fclose($file);

        return $registers;

    }

    public function insertUser($user) : string
    {
        $user = implode(",", $user);
        Storage::append('users.txt', $user);
        return $user;
    }

    public function updateUser($user, $email) : array
    {
        $filePath = storage_path("app/users.txt");
        $users = file($filePath);
        $userFounded = null;

        foreach ($users as $key => $value) {

            if(stristr($value, $email) !== false) {
                $userFounded = $key;
                unset($users[$key]);
                break;
            }
        }

        if (!is_null($userFounded)) {
            array_splice($users, $userFounded, 0, implode(',', $user) ."\r\n");
        }

        $newUsers = array_values($users);

        file_put_contents($filePath, $newUsers);

        if (!is_null($userFounded)) {
            return $user;
        } else {
            return [];
        }

    }

    public function destroyUser($email) : void
    {

        $filePath = storage_path("app/users.txt");

        $users = file($filePath);

        foreach ($users as $key => $value) {

            if(stristr($value, $email) !== false) {
                unset($users[$key]);
                break;
            }
        }

        $newUsers = array_values($users);
        file_put_contents($filePath, implode($newUsers));

    }
}
