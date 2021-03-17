<?php
namespace WpApi;

class Api
{
    CONST URL = "https://whatsapp.securedatainfo.com/api/";
    private $token;
    public function __construct($token)
    {
        $this->token = $token;
    }
    public function GET($method = "",$data=[])
    {
        if(empty($data['token'])){
            $data['token'] = $this->token;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,self::URL.$method.'?'.http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        return $server_output;
    }
    public function POST($method, $data){
        if(empty($data['token'])){
            $data['token'] = $this->token;
        }

        if(!empty($data['file'])) {
            return $this->upload($method,$data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_URL,self::URL.$method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        var_dump($server_output);
        curl_close ($ch);
        return $server_output;
    }


    public function messages($opt){
        try{
            return $this->GET('message/list',$this->options($opt));
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
    public function incoming_messages($opt){
        try{
            return $this->GET('message/incoming',$this->options($opt));
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
    public function show_message($id){
        try{
            return $this->GET('message/'.$id.'/show');
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
    public function send_message($opt){
        try{
            return $this->POST('message/send',$this->options($opt));
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
    public function send_code($opt){
        try{
            return $this->POST('message/send_code',$this->options($opt));
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
    public function options($data){

        foreach($data as $key => $val){
            if(empty($data[$key])){
                unset($data[$key]);
            }
        }
        return $data;
    }
    public function upload($method,$data){
        if(empty($data['token'])){
            $data['token'] = $this->token;
        }
        $curl = curl_init();

        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;

        $post_data = $this->build_data_files($boundary, $data);


        curl_setopt_array($curl, array(
            CURLOPT_URL => self::URL.$method,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                //"Authorization: Bearer $TOKEN",
                "Content-Type: multipart/form-data; boundary=" . $delimiter,
                "Content-Length: " . strlen($post_data)

            ),


        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function build_data_files($boundary, $fields){
        $data = '';
        $eol = "\r\n";
        $file = $fields['file'];
        unset($fields['file']);
        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            if(is_array($content)){
                foreach($content as $veriable):
                    $data .= "--" . $delimiter . $eol
                        . 'Content-Disposition: form-data; name="' . $name . "[]\"".$eol.$eol
                        . $veriable . $eol;
                endforeach;
            }else{
                $data .= "--" . $delimiter . $eol
                    . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
                    . $content . $eol;
            }

        }



        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="file"; filename="' . basename($file) . '"' . $eol
            . 'Content-Type: '.$this->get_mime_type(basename($file)).''.$eol
            . 'Content-Transfer-Encoding: binary'.$eol
        ;

        $data .= $eol;
        $data .= file_get_contents($file) . $eol;

        $data .= "--" . $delimiter . "--".$eol;


        return $data;
    }
    public function get_mime_type($filename) {
        $idx = explode( '.', $filename );
        $count_explode = count($idx);
        $idx = strtolower($idx[$count_explode-1]);

        $mimet = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        if (isset( $mimet[$idx] )) {
            return $mimet[$idx];
        } else {
            return 'application/octet-stream';
        }
    }


}