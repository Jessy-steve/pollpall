<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Services;
use App\Libraries\Hash;

class Auth extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
        
    }

    public function index()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function forgot_password()
    {
        return view('auth/forgot_password');
    }


    public function save()
    {
        //let's validate requests
        //$validation = $this->validate([
        //    'name'=> 'required',
        //    'email'=> 'required|valid_email|is_unique[tbl_users.email]',
        //    'password'=> 'required|min_length[5]|max_length[12]',
        //    'cpassword'=> 'required|min_length[5]|max_length[12]|matches[password]'
        //]);

        $validation = $this->validate([
            'name'=>[
                'rules'=> 'required',
                'errors'=> [
                    'required'=> 'Your full name is required'
                ]
                ],
            'email'=> [
                'rules'=> 'required|valid_email|is_unique[tbl_users.email]',
                'errors'=> [
                    'required'=> 'Email is required',
                    'valid_email'=> 'You must enter a valid email',
                    'is_unique'=> 'Email already taken'
                ]
                ],
            'password'=> [
                'rules'=> 'required|min_length[5]|max_length[12]',
                'errors'=> [
                    'required'=> 'Password is required',
                    'min_length'=> 'Password must have atleast 5 characters',
                    'max_length'=> 'Password must have a maximum of 12 characters'
                ]
                ],
            'cpassword'=> [
                'rules'=> 'required|min_length[5]|max_length[12]|matches[password]',
                'errors'=> [
                    'required'=> 'Confirm password is required',
                    'min_length'=> 'Confirm password must have atleast 5 characters',
                    'max_length'=> 'Confirm password must have a maximum of 12 characters',
                    'matches'=> 'Confirm password does not match the password'
                ]
            ]            
        ]);

        if(!$validation){
            return view('auth/register',['validation'=>$this->validator]);
        }else{
            //Let's register values into the db
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $activationCode = $this->generateActivationCode(); // Generate activation code

            $values = [
                'user_name'=> $name,
                'email'=> $email,
                'password'=> Hash::make($password),
                'activation_code' => $activationCode,
                'active' => 0 // Set active field to 0 (inactive)
            ];

            $usersModel = new \App\Models\UsersModel();
            $query = $usersModel->insert($values);
            if(!$query){
                return redirect()->back()->with('failed', 'Something went wrong');
                // return redirect()->to()->with('failed', 'Something went wrong'); 
            }else{
                // Send activation email to the user
                $activationLink = base_url('auth/activate/' . $activationCode); // Activation link
                $email = \Config\Services::email();
                $userEmail = $this->request->getPost('email');
                $email->setFrom('pallypolly8@gmail.com', 'polly pally');
                $email->setTo($userEmail);
                $email->setSubject('Account Activation');
                $email->setMessage('Please click the following link to activate your account: ' . $activationLink);
                $email->send();

                return redirect()->to('auth/register')->with('success', 'You have been registered successfully. Please check your email to activate your account.');
            }
        }
    }

    private function generateActivationCode()
    {
        // Generate a unique activation code
        // You can use any method to generate a unique code (e.g., random string, UUID, etc.)
        return bin2hex(random_bytes(16));
    }

    public function activate($activationCode)
{
    $usersModel = new \App\Models\UsersModel();
    $user = $usersModel->where('activation_code', $activationCode)->first();

    if (!$user) {
        return redirect()->to('/auth/register')->with('failed', 'Invalid activation code');
    }

    $user['active'] = 1; // Activate the user
    $user['activation_code'] = null; // Remove the activation code

    $usersModel->update($user['user_id'], $user);

    return redirect()->to('/auth')->with('success', 'Your account has been activated. You can now log in.');
}

    private function generateUniqueToken() {
        $prefix = 'remember_'; // Prefix for the token (optional)
        $token = $prefix . uniqid(); // Generate a unique token using uniqid()
    
        return $token;
    }

    function check(){
        //Let's start validation
        $validation = $this->validate([
            'email'=> [
                'rules'=> 'required|valid_email|is_not_unique[tbl_users.email]',
                'errors'=> [
                    'required'=> 'Email is required',
                    'valid_email'=> 'Enter a valid email address',
                    'is_not_unique'=> 'This email is not registered in our system'
                ]
                ],
            'password'=> [
                'rules'=> 'required|min_length[5]|max_length[12]',
                'errors'=> [
                    'required'=> 'Password is required',
                    'min_length'=> 'Password must have atleast 5 characters',
                    'max_length'=> 'Password must have a maximum of 12 characters'
                ]
            ]
        ]);

        if(!$validation){
            return view('auth/login', ['validation'=>$this->validator]);
        }else{
            //let's check user

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $usersModel = new \App\Models\UsersModel();
            $user_info = $usersModel->where('email', $email)->first();
            $check_password = Hash::check($password, $user_info['password']);

            if(!$check_password){
                session()->setFlashdata('failed', 'Incorrect password');
                return redirect()->to('/auth')->withInput();
            }else{
                $user_id = $user_info['user_id'];
                session()->set('loggedUser', $user_id);

                if ($this->request->getPost('remember_me')) {
                    $remember_token = $this->generateUniqueToken();
                    $user_info['remember_token'] = $remember_token;
                    $user_info['remember_email'] = $email;
                    $user_info['remember_password'] = $password;
                    $usersModel->update($user_info['user_id'], $user_info);
                
                    // Set the remember me cookie
                    setcookie('remember_token', $remember_token, time() + 604800, '/');
                } else {
                    // If remember me is not checked, remove any existing remember token and its cookie
                    $user_info['remember_token'] = null;
                    $user_info['remember_email'] = null;
                    $user_info['remember_password'] = null;
                    $usersModel->update($user_info['user_id'], $user_info);
                    setcookie('remember_token', '', time() - 3600, '/');
                }

                return redirect()->to('/dashboard');
            }
        }
    }

    public function send_reset_link()
{
    // Load the necessary helpers and libraries
    helper('form');
    helper('url');
    helper('email');

    // Validate the email address
    $validationRules = [
        'email' => 'required|valid_email',
    ];

    if (!$this->validate($validationRules)) {
        return redirect()->back()->with('failed', 'Please provide a valid email address.');
    }

    // Get the email entered by the user
    $emailAddress = $this->request->getPost('email');

    // Check if the email exists in the database
    $usersModel = new \App\Models\UsersModel();
    $user = $usersModel->where('email', $emailAddress)->first();

    if (!$user) {
        return redirect()->back()->with('failed', 'Email address not found in our records.');
    }

    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(32));

    // Save the token and email in the database or any other storage mechanism of your choice
    // ...

    // Compose the email message
    $subject = 'Password Reset Link';
    $message = 'Please click on the following link to reset your password: ' . site_url('auth/reset_password/' . $token);

    // Configure and send the email
    $emailConfig = [
        'mailType' => 'html',
    ];

    $emailService = \Config\Services::email($emailConfig); // Use the email service with the provided configuration
    $emailService->setTo($emailAddress);
    $emailService->setFrom('pallypolly8@gmail.com', 'polly pally');
    $emailService->setSubject($subject);
    $emailService->setMessage($message);

    if ($emailService->send()) { // Check the result of the send() method
        return redirect()->back()->with('success', 'A password reset link has been sent to your email address.');
    } else {
        return redirect()->back()->with('failed', 'Failed to send the password reset link. Please try again.');
    }
}

public function process_reset_password()
{
    // Retrieve new password and confirm password from form submission
    $newPassword = $this->request->getPost('new_password');
    $confirmPassword = $this->request->getPost('confirm_password');

    // Validate form input
    $validationRules = [
        'new_password' => 'required|min_length[8]|max_length[255]',
        'confirm_password' => 'required|matches[new_password]'
    ];

    if (!$this->validate($validationRules)) {
        // Form validation failed, redirect back to reset password page with errors
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Retrieve the user's email or user ID from the session (assuming it's stored there during the reset password request)
    $userId = session('reset_user_id');

    // Update the user's password in the database
    $usersModel = new \App\Models\UsersModel();
    $user = $usersModel->find($userId);
    $user['password'] = Hash::make($newPassword, PASSWORD_DEFAULT);

    // Save the updated user data
    if (!$usersModel->save($user)) {
        // Error occurred during database update, handle accordingly
        return redirect()->back()->withInput()->with('error', 'Failed to update password. Please try again.');
    }

    // Password updated successfully, redirect to the login page with success message
    return redirect()->to('/auth')->with('success', 'Password reset successful. Please log in with your new password.');
}

public function logout()
{
    // Destroy all sessions
    session()->destroy();

    // Redirect to the login page
    return redirect()->to('/auth');
}


}