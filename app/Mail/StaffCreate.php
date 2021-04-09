<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffCreate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $dummyPassword;
    public $subject;

    public function __construct($user, $dummyPassword, $subject)
    {
        $this->user = $user;
        $this->dummyPassword = $dummyPassword;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(['address'=>'ashraf.emon143@gmail.com', 'name'=> 'Appointment Limited'])->subject($this->subject)->view('emails.create_staff')
            ->with([
                'user' => $this->user,
                'dummyPassword' => $this->dummyPassword
            ]);
    }
}
