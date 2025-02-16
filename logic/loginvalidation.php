<?php
class Validation
{
    // Function to validate login for a specific role
    public static function validateLogin($auth_token, $role_id, $exiturl)
    {
        if (empty($auth_token)) {
            header('Location: ' . $exiturl);
            exit;
        }

        $url = 'https://ngolab.id/api/users';

        // cURL initialization
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $auth_token
        ]);

        // Execute and decode response
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        if ($result['success'] !== true || $result['data']['role_id'] != $role_id) {
            header('Location: ' . $exiturl);
            exit;
        }

        return $result;
    }

    // Specific methods for each role
    public static function validateLoginAdmin($auth_token, $exiturl)
    {
        return self::validateLogin($auth_token, 1, $exiturl);  // Admin role ID is 1
    }

    public static function validateLoginCashier($auth_token, $exiturl)
    {
        return self::validateLogin($auth_token, 2, $exiturl);  // Cashier role ID is 2
    }

    public static function validateLoginOperational($auth_token, $exiturl)
    {
        return self::validateLogin($auth_token, 3, $exiturl);  // Operational role ID is 3
    }

    public static function validateLoginUnrole($auth_token, $exiturl)
    {
        return self::validateLogin($auth_token, 404, $exiturl);  // Unrole ID is 404
    }

    // Checking user login and redirecting based on their role
    public static function isLogin($auth_token, $adminUrl, $cashierUrl, $operationalUrl, $unroleUrl)
    {
        if (isset($auth_token) && !empty($auth_token)) {
            try {
                $url = 'https://ngolab.id/api/users';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: ' . $auth_token
                ]);

                $response = curl_exec($ch);
                $result = json_decode($response, true);
                curl_close($ch);

                // Redirecting based on the user's role
                if ($result['success'] === true) {
                    switch ($result['data']['role_id']) {
                        case 1: // Admin
                            header('Location: ' . $adminUrl);
                            exit;
                        case 2: // Cashier
                            header('Location: ' . $cashierUrl);
                            exit;
                        case 3: // Operational
                            header('Location: ' . $operationalUrl);
                            exit;
                        default:
                            header('Location: ' . $unroleUrl);
                            exit;
                    }
                }
            } catch (Exception $e) {
                // Handle exception
                echo $e->getMessage();
            }
        }
    }

    // Fetching logged-in user's data
    public static function getLoginUser($auth_token)
    {
        $url = 'https://ngolab.id/api/users';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $auth_token
        ]);

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        return $result;
    }

    // Logout the user
    public static function logout($auth_token)
    {
        $url = 'https://ngolab.id/api/users/logout';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $auth_token
        ]);

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        return $result;
    }
}
?>
