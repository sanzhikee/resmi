<?php

class ApiController extends AController
{
    public function actionList()
    {
        // Get the respective model instance
        switch ($_GET['model']) {
            case 'phones':
                $models = Phones::model()->findAll();
                break;
            default:
                // Model not implemented error
                $this->sendResponse(501, sprintf(
                    'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                    $_GET['model']) );
                Yii::app()->end();
        }
        // Did we get some results?
        if (empty($models)) {
            // No
            $this->sendResponse(200, CJSON::encode([
                'success' => true,
                'items' => [],
            ]));
        } else {
            // Prepare response
            $rows = array();
            foreach ($models as $model)
                $rows[] = $model->attributes;
            // Send the response
            $this->sendResponse(200, CJSON::encode([
                'success' => true,
                'items' => $rows,
            ]));
        }
    }

    public function actionView()
    {
        // Check if id was submitted via GET
        if (!isset($_GET['id']))
            $this->sendResponse(500, 'Error: Parameter <b>id</b> is missing');

        switch ($_GET['model']) {
            // Find respective model
            case 'phones':
                $model = Phones::model()->findByPk($_GET['id']);
                break;
            default:
                $this->sendResponse(501, sprintf(
                    'Mode <b>view</b> is not implemented for model <b>%s</b>',
                    $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if (is_null($model))
            $this->sendResponse(404, 'No Item found with id ' . $_GET['id']);
        else
            $this->sendResponse(200, CJSON::encode($model));
    }

    public function actionCreate()
    {
        switch ($_GET['model']) {
            // Get an instance of the respective model
            case 'phones':
                $model = new Phones;
                break;
            default:
                $this->sendResponse(501,
                    sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
        // Try to assign POST values to attributes
        $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
        $post = CJSON::decode($json,true);  //true means use associative array

        foreach ($post as $var => $value) {
            // Does the model have this attribute? If not raise an error
            if ($model->hasAttribute($var))
                $model->$var = $value;
            else
                $this->sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
        }
        // Try to save the model
        if ($model->save())
            $this->sendResponse(200, CJSON::encode($model));
        else {
            // Errors occurred
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error)
                    $msg .= "<li>$attr_error</li>";
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->sendResponse(500, $msg);
        }
    }

    public function actionUpdate()
    {
        // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
        $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
        $put_vars = CJSON::decode($json,true);  //true means use associative array

        switch($_GET['model'])
        {
            // Find respective model
            case 'phones':
                $model = Phones::model()->findByPk($_GET['id']);
                break;
            default:
                $this->sendResponse(501,
                    sprintf( 'Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
                        $_GET['model']) );
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if($model === null)
            $this->sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $_GET['id']) );

        // Try to assign PUT parameters to attributes
        foreach($put_vars as $var=>$value) {
            // Does model have this attribute? If not, raise an error
            if($model->hasAttribute($var))
                $model->$var = $value;
            else {
                $this->sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
                        $var, $_GET['model']) );
            }
        }
        // Try to save the model
        if($model->save())
            $this->sendResponse(200, CJSON::encode($model));
        else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't update model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error)
                    $msg .= "<li>$attr_error</li>";
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->sendResponse(500, $msg);
        }
    }

    public function actionDelete()
    {
        switch($_GET['model'])
        {
            // Load the respective model
            case 'phones':
                $model = Phones::model()->findByPk($_GET['id']);
                break;
            default:
                $this->sendResponse(501,
                    sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
                        $_GET['model']) );
                Yii::app()->end();
        }
        // Was a model found? If not, raise an error
        if($model === null)
            $this->sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $_GET['id']) );

        // Delete the model
        $num = $model->delete();
        if($num>0)
            $this->sendResponse(200, $num);    //this is the only way to work with backbone
        else
            $this->sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $_GET['id']) );
    }
}