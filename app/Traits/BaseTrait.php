<?php
namespace App\Traits;
use Illuminate\Support\Facades\Storage;
trait BaseTrait {

    /**
     * @param $data  数据
     * @param $code  状态码
     * @param string $msg   提示信息
     * @param int $status http状态码
     * @return \Illuminate\Http\JsonResponse
     */
    public function result($data, $code, $msg = '',int $status)
    {
        $result = [
            'data' => $data,
            'code' => $code,
            'message'  => $msg,
            'status' => $status
        ];

        return response()->json($result);
    }

    /**
     * @param $request
     * @param $field
     * @param $directoryName 目录名
     * @param $fileName 文件名
     * @return mixed
     */
    public function upload($request, $field, $directoryName, $fileName = '')
    {
        $file = $request->file($field);
        if($request->hasFile($field) && $file->isValid()){
            if(!$fileName){
                $fileName = time().mt_rand(999, 9999);
            }
            $txt = $file->getClientOriginalExtension();

            $path = $file->storeAs($directoryName, $fileName.'.'.$txt, 'public');

            return $path;
        }
    }

    /**
     * @param $base64String base字符串
     * @param $directoryName 目录名
     * @param string $fileName 文件名
     * @return string 文件地址
     */
    public function uploadBase($base64String, $directoryName, $fileName = '')
    {
        $base64 = substr(strstr($base64String,','),1);
        $data = base64_decode($base64);


        if(!$fileName){
            $fileName = time().mt_rand(999, 9999);
        }

        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64String, $result);

        $path = $directoryName.'/'.$fileName.'.'.$result['2'];
        
        $result = Storage::disk('public')->put($path, $data);

        if($result){
            return $path;
        }
    }

    public function pathToBase64($path)
    {
        $type = pathinfo($path,PATHINFO_EXTENSION );

        $data = Storage::disk('public')->get($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return env('APP_IMAGE_URL').$base64;
    }


}
