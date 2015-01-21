<?php

class View extends Response {

	protected $template;
	protected $vars = array();

	public function __construct($template, $vars = array())
	{
		$this->template = $template;
		$this->vars = $vars;
	}

	/**
	 * @return mixed
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	public function getTemplateFileName()
	{
		return PATH_APP.'views/' . $this->getTemplate() . '.tpl.php';
	}

	/**
	 * @return array
	 */
	public function getVars()
	{
		return $this->vars;
	}

	public function execute()
	{
		$template         = $this->getTemplate();
		$templateFileName = $this->getTemplateFileName();
		$vars             = $this->getVars();

		//var_dump($templateFileName);

		if ( ! file_exists($templateFileName))
		{
			$return = [
				'success' => false,
				'error'   => 'Vista no existe '. $template
			];
			exit(json_encode($return));
		}

		call_user_func(function () use ($templateFileName, $vars) {
			extract($vars);

			ob_start();

			require $templateFileName;

			if (!$is_template) {
				$tpl_content = ob_get_clean();
				require PATH_APP."views/layout.tpl.php";
			}
		});
	}

}