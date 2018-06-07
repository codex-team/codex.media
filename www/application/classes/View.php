<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 * @author CodeX Team
 * @copyright (c) 2018 CodeX Team
 *
 * CodeX Media Core View Class
 *
 * inherit main Kohana_View class set_filename method to use project path if exists
 * or use application view by default
 *
 * If both project and application doesn't contain required file throw an View_Exception
 */
class View extends Kohana_View {

    /**
     * @var string
     */
    protected $view_path = 'views';

    /**
     * Sets the view filename.
     *
     *     $view->set_filename($file);
     *
     * @param   string  $file   view filename
     * @return  View
     * @throws  View_Exception
     */
    public function set_filename($file)
    {
        $application_path = Kohana::find_file($this->view_path, $file);
        $project_path = Kohana::find_file( '../projects' . DIRECTORY_SEPARATOR . $_SERVER['PROJECT'] . DIRECTORY_SEPARATOR . $this->view_path, $file);

        if ( $application_path === FALSE && $project_path === FALSE )
        {
            throw new View_Exception('The requested view :file could not be found', array(
                ':file' => $file,
            ));
        }

        // Store the file path locally
        if ($project_path === FALSE) {
            $this->_file = $application_path;
        } else {
            $this->_file = $project_path;
        }

        return $this;
    }

}