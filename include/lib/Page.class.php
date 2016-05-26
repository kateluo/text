<?php

/**
 * 分页控制
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/19
 * Time: 9:55
 */
class Page
{

    private $all_num_rows;//总共的数据

    public $page_num_rows;//每页显示数据的数据

    private $all_num_pages;//总的页数
    /**当前页数
     * @var int|number
     */
    private $this_page;//当前的页数
    /**数据起始行数
     * @var int|number
     */
    public $first_rows;//起始数据

    private $page_num_buttons;//每页的按钮数量

    /**显示分页按钮数量
     * Page constructor.
     * @param $all_num_rows
     * @param int $page_num_roms
     */
    private $page_name;

    public function __construct($all_num_rows, $page_num_roms = 10, $page_num_buttons = 10, $page_name = 'page')
    {

        $this->page_name = $page_name;
        $this->all_num_rows = $all_num_rows;
        $this->page_num_rows = !empty($page_num_roms) ? $page_num_roms : 10;
        //计算总页数
        $this->all_num_pages = intval(ceil($this->all_num_rows / $this->page_num_rows));
        //获取当前页数
        $this_page = $this->get_this_page();
        //修正当前页数
        $this->this_page = ($this_page <= $this->all_num_pages) ? $this_page : $this->all_num_pages;

        //获取起始位置

        $this->first_rows = ($this->this_page - 1) * $this->page_num_rows;

        $this->page_num_buttons = $page_num_buttons >= $this->all_num_pages ? $this->all_num_pages : $page_num_buttons;
        //按钮数

    }

    /**get_this_page 获取当前页数
     * @return int|number
     *  当前页数，整形，默认第一页
     */

    private function get_this_page()
    {
        $page = isset($_REQUEST[$this->page_name]) ? abs(intval($_REQUEST[$this->page_name])) : 1;
        $page = !empty($page) ? $page : 1;
        return $page;
    }

    private function get_query_string()
    {
        //获取请求
        $string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        //var_dump($string);
        //解析成数组
        parse_str($string, $arr);
        //卸载当前页数
        unset($arr[$this->page_name]);
        //从新编译url_encode
        $xx = http_build_query($arr);
        return !empty($xx) ? '?' . $xx . '&' : '?';
        var_dump($arr);
    }

    public function show()
    {
        //如果只有一页，没必要显示
        if ($this->all_num_pages==1 || $this->all_num_rows==0){
            return'';//返回空
        }

        $url = $this->get_query_string();
        $html = '<nav><ul class="pagination">';
        //显示头部
        if (1 >= $this->this_page) {

            $html .= '<li class="disabled"><a href="javascript:void(0)">首页</a>';
            $html .= '<li class="disabled"><a href="javascript:void(0)">上页</a> ';
        } else {
            $html .= '<li><a href="' . $url . $this->page_name . '=1">首页</a>';
            $html .= '<li><a href="' . $url . $this->page_name . '=' . ($this->this_page - 1) . '">上页</a>';
        }
        $c = intval($this->page_num_buttons / 2);
        //起始显示位置
        if ($this->this_page <= $this->page_num_buttons - $c && $this->this_page<=$this->all_num_pages-($this->page_num_buttons-$c)) {
            for ($i = 1; $i <= $this->page_num_buttons; $i++) {
                if ($this->this_page == $i) {
                    $html .= '<li class="active"><a href="' . $url . $this->page_name . '=' . $i . '">' . $i . '</a>';
                } else {
                    $html .= '<li><a href="' . $url . $this->page_name . '=' . $i . '">' . $i . '</a> ';
                }
            }
        }
        //中间显示的数量
        if ($this->this_page > $this->page_num_buttons - $c && $this->this_page <= $this->all_num_pages - $this->page_num_buttons + $c) {


            for ($i = $this->this_page - $c; $i <= $this->this_page + $c; $i++) {
                if ( $this->this_page==$i) {
                    $html .= '<li class="active"><a href="' . $url . $this->page_name . '=' . $i . '">' . $i . '</a> ';
                } else {
                    $html .= '<li><a href="' . $url . $this->page_name . '=' . $i . '">' . $i . '</a> ';
                }
            }
        }
        //判断最后几个显示的数量
        if ($this->this_page > $this->all_num_pages - $this->page_num_buttons + $c) {
            for ($i = $this->all_num_pages - $this->page_num_buttons + 1; $i <= $this->all_num_pages; $i++) {
                if ($this->this_page == $i) {
                    $html .= '<li class="active"><a href="' . $url . $this->page_name . '=' . $i . '">' . $i . '</a>';
                } else {
                    $html .= '<li><a href="' . $url . $this->page_name . '=' . $i . '">' . $i . '</a>';
                }

            }
        }
        //显示末尾
        if ($this->this_page >=$this->all_num_pages) {
            $html .= '<li class="disabled"><a href="javascript:void(0)">下页</a> ';
            $html .= '<li class="disabled"><a href="javascript:void(0)">末页</a> ';
        } else {
            $html .= '<li><a href="' . $url . $this->page_name . '=' . ($this->this_page + 1) . '">下页</a>';
            $html .= '<li><a href="' . $url . $this->page_name . '=' . $this->all_num_pages . '">末页</a> ';
        }

        $html .= '</ul></nav>';
        return $html;


    }

    public function test()
    {
        return $this->get_query_string();
    }
}