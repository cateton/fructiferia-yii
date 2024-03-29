<?php

class ProductoController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array('accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'), 'users' => array('*'),), array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'agregarImagen', 'eliminarImagen'), 'users' => array('@'),), array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'), 'users' => array('admin'),), array('deny', // deny all users
                'users' => array('*'),),);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.fancybox/jquery.fancybox.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/funciones.js');

        $imagenes = new Imagen;
        $ruta = new Configuracion;

        $this->render('view', array('model' => $this->loadModel($id), 'imagenes' => $imagenes->findAll('producto_id = ' . $id), 'ruta' => $ruta->findByPk(1)->configuracion_valor));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Producto;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Producto'])) {
            $model->attributes = $_POST['Producto'];
            $model->producto_fecha_ingreso = new CDbExpression('NOW()');

            if ($model->save()) {
                $model->producto_codigo = 'PR-' . str_pad($model->producto_id, 5, "0", STR_PAD_LEFT);
                $model->save();

                $this->redirect(array('view', 'id' => $model->producto_id));
            }
        }

        $this->render('create', array('model' => $model,));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Producto'])) {
            $model->attributes = $_POST['Producto'];
            $model->producto_fecha_modificacion = new CDbExpression('NOW()');
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->producto_id));
        }

        $this->render('update', array('model' => $model,));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Producto');
        $this->render('index', array('dataProvider' => $dataProvider,));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Producto('search');
        $model->unsetAttributes();
        // clear any default values
        if (isset($_GET['Producto']))
            $model->attributes = $_GET['Producto'];

        $this->render('admin', array('model' => $model,));
    }

    public function actionAgregarImagen($id) {
        $this->layout = 'ajax';

        $model = new ImagenProducto;
        $imagen = new Imagen;
        $ruta = new Configuracion;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='imagen-producto-agregarImagen-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['ImagenProducto'])) {
            $model->attributes = $_POST['ImagenProducto'];
            //echo "<pre>"; print_r($_FILES); echo "</pre>"; exit();
            //echo "<pre>"; print_r($_POST); echo "</pre>";
            $imagen->producto_id = $id;

            if ($imagen->save()) {
                $imagen_nombre = 'PI-' . str_pad($imagen->imagen_id, 6, "0", STR_PAD_LEFT) . '-' . str_replace(' ', '_', $_FILES["ImagenProducto"]["name"]['archivo']);
                $imagen->imagen_nombre = $imagen_nombre;

                if ($imagen->save()) {
                    if (move_uploaded_file($_FILES["ImagenProducto"]["tmp_name"]['archivo'], $ruta->findByPk(1)->configuracion_valor . '/' . $imagen_nombre)) {
                        $this->redirect(array('producto/view', 'id' => $id));
                    }
                }
            }
        }

        $this->render('agregarImagen', array('model' => $model, 'id' => $id));
    }

    public function actionEliminarImagen($id) {
        $imagen = Imagen::model()->findByPk($_GET['imgid']);
        //echo Configuracion::model()->findByPk(1)->configuracion_valor; exit();
        @unlink(Configuracion::model()->findByPk(1)->configuracion_valor . '\\' . $imagen->imagen_nombre);
        $imagen->delete();

        $this->redirect(array('producto/view', 'id' => $id));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Producto::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'producto-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
