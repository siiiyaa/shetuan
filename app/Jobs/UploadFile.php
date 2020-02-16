<?php

namespace App\Jobs;

use App\Traits\BaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use BaseTrait;

    protected $request;
    protected $field;
    protected $directoryName;
    protected $fileName;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $field, $directoryName, $fileName = '')
    {
        $this->request = $request;
        $this->field = $field;
        $this->directoryName = $directoryName;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dd(1);
        $path = $this->upload($this->request,$this->field,$this->directoryName,$this->fileName);
        return $path;
    }
}
