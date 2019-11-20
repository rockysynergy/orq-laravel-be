<?php

namespace Orq\Laravel\Starter\Controllers;

use Illuminate\Http\Request;
use Orq\Laravel\Starter\Model\File;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\MicroGroup\Domain\Service\ExproductService;

class FileController extends Controller
{
    /**
     * 处理上传文件
     * https://blog.csdn.net/koastal/article/details/80668260
     */
    public function uploadFile($postFile = 'upload', Request $request)
    {
        // $allowedPrefix = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'ppt', 'pptx', 'rar', 'pdf'];
        $allowedPrefix = ['jpg', 'jpeg', 'png', 'mp3', 'mpga'];
        //检查文件是否上传成功
        $postFile = $request->input('name') ? $request->input('name') : $postFile;
        if (!$request->hasFile($postFile) || !$request->file($postFile)->isValid()) {
            return $this->CKEditorUploadResponse(0, '文件上传失败');
        }
        $extension = $request->file($postFile)->extension();
        Log::error('Uploaded extension: '.$extension);
        $size = $request->file($postFile)->getSize();
        $filename = $request->file($postFile)->getClientOriginalName();

        $fileKey = $request->input('fileKey');
        // 检查是否已经存在
        $existUrl = $this->fileUrl($request->file($postFile)->getPathname());
        if (!is_null($existUrl)) {
            return isset($fileKey) ? $this->wxFileUploadResponseSuccess($existUrl, $fileKey) : $this->CKEditorUploadResponse(1, '', $filename, $existUrl);
        }

        //检查后缀名
        if (!in_array($extension, $allowedPrefix)) {
            $msg = '文件类型不合法';
            return isset($fileKey) ? $this->wxFileUploadResponseFail($msg) : $this->CKEditorUploadResponse(0, $msg);
        }
        //检查大小
        if ($size > 10 * 1024 * 1024) {
            $msg = '文件大小超过限制';
            return  isset($fileKey) ? $this->wxFileUploadResponseFail($msg) : $this->CKEditorUploadResponse(0, $msg);
        }
        //保存文件
        //@todo add resizing
        $url = $request->file($postFile)->store('images_upload', 'public');
        $this->storeFileRecord($url);
        $url = '/storage/'.$request->file($postFile)->store('images_upload', 'public');

        return  isset($fileKey) ? $this->wxFileUploadResponseSuccess($url, $fileKey) : $this->CKEditorUploadResponse(1, '', $filename, $url);
    }

    protected function wxFileUploadResponseSuccess($url, $fileKey) {
        return response()->json(['code'=>0, 'msg'=>'success', 'data'=>['url'=>$url, 'fileKey'=>$fileKey]]);
    }

    protected function wxFileUploadResponseFail($msg) {
        return response()->json(['code'=>1, 'msg'=>$msg, 'data'=>[]]);
    }

    public function listFiles()
    {
        $files = Storage::disk('public')->files('images_upload');
        foreach($files as $file) {
            // var_dump($file);
            $arr = ['name'=>$file, 'hash'=>sha1_file(Storage::disk('public')->url($file))];
            var_dump($arr);
        }
    }

    /**
     * 查询数据库看文件是不是已经上传
     */
    protected function fileUrl($filePath)
    {
        $hash = \sha1_file($filePath);
        $f = File::where('hash', $hash)->first();
        if ($f) {
            return $f->url;
        } else {
            return null;
        }

    }

    /**
     * 存储文件记录
     */
    protected function storeFileRecord($url)
    {
        $hash = sha1_file(Storage::disk('public')->url($url));
        $f = new File();
        $f->hash = $hash;
        $f->url = '/storage/'.$url;

        $f->save();
    }

    /**
     * CKEditor 上传文件的标准返回格式
     * @param string $uploaded [description]
     * @param string $error    [description]
     * @param string $filename [description]
     * @param string $url      [description]
     */
    private function CKEditorUploadResponse($uploaded, $error = '', $filename = '', $url = '')
    {
        return [
            "uploaded" => $uploaded,
            "fileName" => $filename,
            "url" => $url,
            "error" => [
                "message" => $error
            ]
        ];
    }

    /**
     * Wechat group qrcode image make testing
     */
    public function makeQrCode()
    {
        ExproductService::makeGroupChatQrCodeImg();
        return wordwrap('长按收藏图片，然后回到微信->我->收藏->长按识别二维码进群', 5, '<br>');
    }
}
