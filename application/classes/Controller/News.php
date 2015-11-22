<?php defined('SYSPATH') or die('No direct script access.');

class Controller_News extends Controller_Base_preDispatch
{
    public function action_index()
    {   
        $this->view['news_error'] = false;
        $this->view['news_error_text'] = '';

        # for switch off error in view
        $this->view['news'] = false;
        $this->view['news_row'] = false;
        $this->view['last_news'] = false;
        $this->view['form'] = false;

        $news_id = $this->request->param('id', 0);

        $news = new Model_News();
        $user = new Model_User();
        
        # from form 'addComment' for news
        $action  = Arr::get($_POST, 'action', '');
        $token   = Arr::get($_POST, 'csrf', '');
        $id_news = Arr::get($_POST, 'news_id', ''); 
        $text    = Arr::get($_POST, 'text', ''); 

        # add comment for news
        if ( $action == 'addComment' ) {
            
            if ( $text && Security::check($token) ) {
                $user->id ? $user = $user->id : $user = 0;

                $add = $news->addComment( $id_news, $user, $text );

                if (!$add) {
                    $this->view['news_error'] = true;
                    $this->view['news_error_text'] = 'Комментарий не добавлен';
                }
    
                $this->redirect('/news/'.$id_news);
            }
            else {
                $this->view['news_error'] = true;
                $this->view['news_error_text'] = 'Поле текста пусто';
            }
        }

        if ( $news_id ) { // show single news item

            # create comments form
            $form  = '<form action="/news/" method="post">';
            $form .=    '<input type="hidden" name="action" value="addComment" / >';
            $form .=    '<input type="hidden" name="news_id" value="'.$news_id.'" / >';
            $form .=    '<input type="hidden" name="csrf" value="' . Security::token(). '">';

            $form .=    '<div class="input_text mt25">';
            $form .=        '<textarea name="text" cols="30" rows="10" placeholder="Текст комментария"></textarea>';
            $form .=    '</div>';
            $form .=    '<input type="submit" class="mt25" value="Отправить" />';
            $form .= '</form>';

            # get all data
            $this->view['news']             = $news->getNews( $news_id );
            $this->view['news_comments']    = $news->getNewsComments( $news_id );
            $this->view['last_news']        = $news->getLastNews( 10 );
            $this->view['form']             = $form;


            $this->template->title = 'КИКГ ИТМО - '.$this->view['news']['title'];

        } else {    // show all news, limit(50)

            $news_row = $this->view['news_row'] = $news->getNews(0, 0);

            if ( !$news_row ) {
                $this->view['news_error'] = true;
                $this->view['news_error_text'] = 'Новости не найдены';
            }

            $this->template->title = 'КИКГ ИТМО - Все новости';
        }

        $this->template->content = View::factory('templates/news', $this->view); 

    }

}