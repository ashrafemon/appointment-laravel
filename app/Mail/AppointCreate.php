<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointCreate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $staff
     * @param $service
     * @param $appoint
     */
    public $user;
    public $staff;
    public $service;
    public $appoint;

    public function __construct($user, $staff, $service, $appoint)
    {
        $this->user = $user;
        $this->staff = $staff;
        $this->service = $service;
        $this->appoint = $appoint;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(['address'=>'ashraf.emon143@gmail.com', 'name'=> 'Appointment Limited'])->view('emails.create_appoint')
            ->with([
                'user' => $this->user,
                'staff' => $this->staff,
                'service' => $this->service,
                'appoint' => $this->appoint,
            ]);
    }
}
