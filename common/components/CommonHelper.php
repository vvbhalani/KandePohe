<?php
/**
 *
 */
namespace common\components;

use common\models\otherlibraries\Compressimage;
use common\models\otherlibraries\Getextension;
use Yii;

class CommonHelper {

    /*function __construct(argument) {

    }*/

    public static function generatePrefix()
    {
        return 'KP';
    }

    public static function generateNumericUniqueToken($number)
    {
        $arr = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $token = "";
        for ($i = 0; $i < $number; $i++) {
            $index = rand(0, count($arr) - 1);
            $token .= $arr[$index];
        }
        return $token;
    }

    public static function  generateUniqueToken($number)
    {
        $arr = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9', '0');
        $token = "";
        for ($i = 0; $i < $number; $i++) {
            $index = rand(0, count($arr) - 1);
            $token .= $arr[$index];
        }
        return $token;
    }

    public static function  pr($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public static function getTime()
    {
        return date('Y-m-d H:i:s');
    }

    public static function ageCalculator($dob)
    {
        //calculate years of age (input string: YYYY-MM-DD)
        list($year, $month, $day) = explode("-", $dob);

        $year_diff = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff = date("d") - $day;

        if ($day_diff < 0 || $month_diff < 0)
            $year_diff;

        return $year_diff;
    }

    public static function photoUpload($iUserId, $FILES, $PATH, $URL, $SIZE_ARRAY, $OLD_PHOTO = '')
    {
        #pr($FILES);exit;
        global $Obj_User1, $tconfig, $inc_class_path, $txt;
        #$USER_PHOTO_FOLDER = self::getUserUploadFolder(1);
        #$USER_PHOTO_FOLDER = $USER_PHOTO_FOLDER."/".$iUserId;
        $USER_PHOTO_FOLDER = $PATH;
        if (!is_dir($USER_PHOTO_FOLDER)) {
            mkdir($USER_PHOTO_FOLDER, 0777);

        }
        $DEL_IMG = $SIZE_ARRAY;//array('',100,200,50);
        #pr($DEL_IMG);exit;
        $path = $PATH;//$tconfig["tsite_upload_images_member_path"].$iUserId."/";exit;
        $URL = $URL;//$tconfig["tsite_upload_images_member_url"].$iUserId."/";
        $PHOTO = '';

        $actual_image_name = "";
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "PNG", "JPG", "JPEG", "GIF", "BMP");
        #include_once($inc_class_path ."includes_photo/getExtension.php");
        $Getextension = new Getextension();
        #include_once 'includes/getExtension.php';
        $imagename = $FILES['name'];
        $size = $FILES['size'];
        #echo "================ ".$size;exit;
        if (strlen($imagename)) {
            $ext = strtolower($Getextension->getExtension($imagename));
            if (in_array($ext, $valid_formats)) {
                /*if($size<(1024*1024))
                {*/
                $actual_image_name = rand(1, 2000) . time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
                $uploadedfile = $FILES['tmp_name'];
                #include_once($inc_class_path ."includes_photo/compressImage.php");
                $compressImage = new Compressimage();
                #include 'includes/compressImage.php';

                $widthArray = $SIZE_ARRAY;// array(200,100,50);
                foreach ($widthArray as $newwidth) {
                    $filename = $compressImage->compressImage($ext, $uploadedfile, $path, $actual_image_name, $newwidth);
                    #unlink($path.$newwidth.'_1469352590');
                    #echo "<img src='".$filename."' class='img'> <br/>";
                    #  echo "<b>Width:</b> ".$newwidth."px  <br/><b>File Name:</br> ".$filename."<br/><br/>";
                }
                #echo " <br> UP FILE => ".$uploadedfile;
                #echo " <br> PATh NAME=> ".$path.$actual_image_name;
                #var_dump(move_uploaded_file($uploadedfile, $path.$actual_image_name));
                if (move_uploaded_file($uploadedfile, $path . $actual_image_name)) {
                    $PHOTO = $actual_image_name;
                    /*$Obj_User1->select($iUserId);
                    $OLD_PHOTO =  $Obj_User1->getVPhoto();
                    $Obj_User1->setVPhoto(addslashes($actual_image_name));*/
                    $UPDATE_FLAG = 1;//$Obj_User1->uploadPhoto($iUserId);
                    if ($UPDATE_FLAG) {
                        if ($OLD_PHOTO != '') {
                            foreach ($DEL_IMG as $k => $V) {
                                if ($k == 0)
                                    $vImage_hid = $OLD_PHOTO;
                                else
                                    $vImage_hid = $V . '_' . $OLD_PHOTO;
                                unlink($path . $vImage_hid);
                            }
                        }
                        $STATUS = 1;
                        $NOTIFICATION_TYPE = 'Success';
                        $NOTIFICATION_MSG = 'Upload Successfully';
                        #$PHOTO = $URL."100_".$actual_image_name;
                    } else {
                        $STATUS = 0;
                        $NOTIFICATION_TYPE = 'Failed';
                        $NOTIFICATION_MSG = 'Something went wrong. Please try again !';
                    }
                } else {
                    $STATUS = 0;
                    $NOTIFICATION_TYPE = 'Failed';
                    $NOTIFICATION_MSG = 'Fail upload folder with read access.';
                }

                //}
                /*else{
                    $STATUS = 0;
                    $NOTIFICATION_TYPE = 'Failed';
                    echo $NOTIFICATION_MSG = 'Image file size max 1 MB.';//exit;
                }*/

            } else {
                $STATUS = 0;
                $NOTIFICATION_TYPE = 'Failed';
                $NOTIFICATION_MSG = 'Invalid file format..';
            }

        }

        $RES_ARRAY = array("STATUS" => $STATUS, "NOTIFICATION_TYPE" => $NOTIFICATION_TYPE, "NOTIFICATION_MSG" => $NOTIFICATION_MSG, "PHOTO" => $PHOTO);
        #print_r($RES_ARRAY);
        return $RES_ARRAY;

    }

    public static function getUserResizeRatio()
    {//For User photo resize
        $USER_SIZE_ARRAY = array('', 30, 140, 200, 350, 500, 900);
        return $USER_SIZE_ARRAY;
    }

    public static function getPhotos($TYPE = 'USER', $ID, $PHOTO, $SIZE)
    {
        /*echo "<br>".$TYPE;
        echo "<br>".$ID;
        echo "<br>".$PHOTO;
        echo "<br>".$SIZE;exit;*/
        if ($TYPE == 'USER') {
            $U_PATH = $ID . "/";
            $PHOTO_WITH_SIZE = $SIZE . "_" . $PHOTO;
            $MAIN_URL = CommonHelper::getUserUploadFolder(2);
            $PATH = CommonHelper::getUserUploadFolder(1) . $U_PATH;
            $URL = $MAIN_URL . $U_PATH;
            $PHOTO_USER = is_file($PATH . $PHOTO_WITH_SIZE) ? $URL . $PHOTO_WITH_SIZE : $MAIN_URL . 'no-user-img.jpg';
            return $PHOTO_USER;
        }
    }

    public static function getUserUploadFolder($TYPE = 1)
    {//For path AND URL
        // User : echo CommonHelper::getUserUploadFolder(2);
        if ($TYPE == 1) {
            $USER_UPLOAD = Yii::getAlias('@frontend') . '/web/uploads/users/';
        } else {
            $USER_UPLOAD = Yii::getAlias('@web') . '/uploads/users/';
        }
        return $USER_UPLOAD;
    }

    public static function getUserDefaultPhoto()
    {
        $MAIN_URL = CommonHelper::getUserUploadFolder(2);
        return $MAIN_URL . 'no-user-img.jpg';
    }

    public function getReligion()
    {
        $religion = \common\models\Religion::find()->all();
        return $religion;
    }

    public function getAnnualIncome()
    {
        return \common\models\AnnualIncome::find()->all();
    }

    public function getWorkingWith()
    {
        return \common\models\WorkingWith::find()->all();
    }

    public function getEducationField()
    {
        return \common\models\EducationField::find()->all();
    }

    public function getEducationLevel()
    {
        return \common\models\EducationLevel::find()->all();
    }

    public function getWorkingAS()
    {
        return \common\models\WorkingAS::find()->all();
    }

    public function getCommunity()
    {
        return \common\models\MasterCommunity::find()->all();
    }

    public function getSubCommunity()
    {
        return \common\models\MasterCommunitySub::find()->all();
    }

    public function getMaritalStatus()
    {
        return \common\models\MasterMaritalStatus::find()->all();
    }

    public function getGotra()
    {
        return \common\models\MasterGotra::find()->all();
    }

    public function getDistrict()
    {
        return \common\models\MasterDistrict::find()->all();
    }

    public function getTaluka()
    {
        return \common\models\MasterTaluka::find()->all();
    }

    public function getCountry()
    {
        return \common\models\Countries::find()->all();
    }

    public function getState($id = '')
    {
        if ($id == '')
            return \common\models\States::find()->all();
        else
            return \common\models\States::find()->where(['iCountryId' => $id])->all();
    }

    public function getCity($id = '')
    {
        if ($id == '')
            return \common\models\Cities::find()->all();
        else
            return \common\models\Cities::find()->where(['iStateId' => $id])->all();

    }

    public function getHeight()
    {
        return \common\models\MasterHeight::find()->all();
    }

    public function getDiet()
    {
        return \common\models\MasterDiet::find()->all();
    }

    public function getFmstatus()
    {
        return \common\models\MasterFmStatus::find()->all();
    }

    function encryptor($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'vCoderTeam';
        $secret_iv = 'vCoderTeam123';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    function truncate($str, $len)
    {
        $tail = max(0, $len - 10);
        $trunk = substr($str, 0, $tail);
        $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($str, $tail, $len - $tail))));
        return $trunk;
    }

    function getFrontUpload($img = '')
    {
        #$target_dir = Yii::getAlias('@frontend') .'/web/uploads/';
        $target_dir = Yii::getAlias('@web') . '/uploads/users/';
        $target_dir_default = Yii::getAlias('@web') . '/images/';
        if ($img != '')
            $img = $target_dir . $img;
        else
            $img = $target_dir_default . 'placeholder.jpg';

        #echo $img;exit;
        return $img;
    }
}
?>