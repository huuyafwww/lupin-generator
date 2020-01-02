<?php

class Lupin{

    private $img;
    private $font_color;
    private $img_path;
    private $frame_path;
    private $string;
    private $strings;
    private $char_count;
    private $img_count = 0;
    private $temp_frame_path_list = "";
    private $temp_files_path = [];

    /**
     * Include the Constant File(Constructor).
     *
     */
    public function __construct()
    {
        include_once(__DIR__."/Define.php");
    }

    /**
     * Init Image Settings.
     *
     */
    private function init_img()
    {
        $this->create_img_stream();
        $this->set_background_color();
        $this->set_font_color();
    }

    /**
     * Creating a GD Image stream.
     *
     */
    private function create_img_stream()
    {
        $this->img = imagecreate(L_IMG_WIDTH,L_IMG_HEIGHT);
    }

    /**
     * Creating a Image File.
     *
     * @param string $char = String to insert into image.
     * @param int $font_size = The size of the character to insert.
     * @param int $img_y = Y-axis value starting from upper left.
     */
    private function create_img(
        $char,
        $font_size = L_FONT_SIZE,
        $img_y = L_IMG_Y
        )
    {
        $this->set_img_path();
        imagettftext(
            $this->img,
            $font_size,
            L_IMG_ANGLE,
            L_IMG_X,
            $img_y,
            $this->font_color,
            L_FONT_FILE,
            $char
        );
        imagepng($this->img,$this->img_path);
    }

    /**
     * Creating a temporary Frame File.
     *
     * @param int $file_name_int = Number of sequential file.
     */
    private function create_frame($file_name_int)
    {
        $file_name = sprintf('%03d',$file_name_int);
        $temp_img_path = L_TEMP_IMAGE_PATH.$file_name.".png";
        $temp_frame_path = L_TEMP_FRAME_PATH.$file_name.".mp4";

        $this->temp_files_path[] = $temp_img_path;
        $this->temp_files_path[] = $temp_frame_path;

        $this->temp_frame_path_list .= "file ".$temp_frame_path."\n";

        $is_title = $file_name_int === $this->char_count;

        $sound_path = $is_title ? L_TITLE_SOUND : L_TYPE_SOUND;
        $sound_length = $is_title ? "00:03.8" : "00:00.2";
        $cmd = L_FFMPEG_COMMAND_PATH." -y -r 5 -loop 1 -i ".$temp_img_path." -i ".$sound_path." -vcodec libx264 -acodec aac -strict experimental -ab 320k -ac 2 -ar 48000 -pix_fmt yuv420p -t ".$sound_length." ".$temp_frame_path;
        `$cmd`;
    }

    /**
     * Creating a temporary Frame List File.
     *
     */
    private function create_frame_list_file()
    {
        file_exists(L_FRAME_LIST_FILE) OR touch(L_FRAME_LIST_FILE);
        file_put_contents(L_FRAME_LIST_FILE,$this->temp_frame_path_list,LOCK_EX);
    }

    /**
     * Set the Background Color at GD image stream.
     *
    */
    private function set_background_color()
    {
        imagecolorallocate(
            $this->img,
            L_BACKGROUND_COLOR_R,
            L_BACKGROUND_COLOR_G,
            L_BACKGROUND_COLOR_B
        );
    }

    /**
     * Set the Font Color.
     *
    */
    private function set_font_color()
    {
        $this->font_color = imagecolorallocate(
            $this->img,
            L_FONT_COLOR_R,
            L_FONT_COLOR_G,
            L_FONT_COLOR_B
        );
    }

    /**
     * Set the temporary Image Path.
     *
    */
    private function set_img_path()
    {
        $file_name = sprintf('%03d',$this->img_count);
        $this->img_path = L_TEMP_IMAGE_PATH.$file_name.".png";
        $this->img_count++;
    }

    /**
     * Delete temporary Files.
     *
    */
    private function delete_files()
    {
        foreach($this->temp_files_path as $file_path)
        {
            unlink($file_path);
        }
    }

    /**
     * Multi Byte String to Array
     *
    */
    private function multi_byte_string_to_array()
    {
        $this->strings = array_values(
            array_filter(
                preg_split("//u",$this->string),
                "strlen"
            )
        );
    }

    /**
     * Get a Typing Image.
     *
     */
    private function get_type_img()
    {
        $this->multi_byte_string_to_array();
        foreach($this->strings as $char)
        {
            $this->init_img();
            $this->create_img($char);
        }
    }

    /**
     * Get a Title Image.
     *
     */
    private function get_title_img()
    {
        $this->char_count = count($this->strings);
        $this->init_img();
        $font_size = L_FONT_SIZE / $this->char_count;
        $img_y = (L_IMG_HEIGHT + $font_size) / 2;
        $this->create_img($this->string,$font_size,$img_y);
    }

    /**
     * Get a Frame.
     *
     */
    private function get_frame()
    {
        for($i = 0;$i <= $this->char_count;$i++)
        {
            $this->create_frame($i);
        }
        $this->create_frame_list_file();
    }

    /**
     * Get a Lupin.
     *
     */
    public function get_lupin($input)
    {
        $this->string = $input;
        $this->get_type_img();
        $this->get_title_img();
        imagedestroy($this->img);
        $this->get_frame();
        $this->output();
        $this->finalize();
    }

    /**
     * Output the result from the created frame.
     *
     */
    private function output()
    {
        $cmd = L_FFMPEG_COMMAND_PATH." -y -f concat -safe 0 -i ".L_FRAME_LIST_FILE." -c copy ".L_OUTPUT_PATH."output.mp4";
        `$cmd`;
    }

    /**
     * Processing to be performed after outputting the result.
     *
     */
    private function finalize()
    {
        $this->temp_files_path[] = L_FRAME_LIST_FILE;
        $this->delete_files();
    }
}