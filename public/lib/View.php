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

	/**
	 * @return array
	 */
	public function getVars()
	{
		return $this->vars;
	}

	public function execute()
	{
		$template = $this->getTemplate();
		$vars = $this->getVars();

		call_user_func(function () use ($template, $vars) {
			extract($vars);

			ob_start();

			require PATH_APP."views/$template.tpl.php";

			if (!$is_template) {
				$tpl_content = ob_get_clean();
				require PATH_APP."views/layout.tpl.php";
			}
		});
	}

}