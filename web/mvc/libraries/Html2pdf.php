<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(__DIR__ . '/dompdf/autoload.inc.php');
require __DIR__ . '/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
class Html2pdf {

    public $CI;
    public $html;
    public $path;
    public $filename;
    public $paper_size;
    public $orientation;

    /**
     * Constructor
     *
     * @access	public
     * @param	array	initialization parameters
     */
    function Html2pdf($params = array())
    {
        $this->CI =& get_instance();

        if (count($params) > 0)
        {
            $this->initialize($params);
        }

        log_message('debug', 'PDF Class Initialized');

    }

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
    function initialize($params)
	{
        $this->clear();
		if (count($params) > 0)
        {
            foreach ($params as $key => $value)
            {
                if (isset($this->$key))
                {
                    $this->$key = $value;
                }
            }
        }
	}

	// --------------------------------------------------------------------

	/**
	 * Set html
	 *
	 * @access	public
	 * @return	void
	 */
	function html($html = NULL)
	{
        $this->html = $html;
	}

	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */
	function folder($path)
	{
        $this->path = $path;
	}

	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */
	function filename($filename)
	{
        $this->filename = $filename;
	}

	// --------------------------------------------------------------------


	/**
	 * Set paper
	 *
	 * @access	public
	 * @return	void
	 */
	function paper($paper_size = NULL, $orientation = NULL)
	{
        $this->paper_size = $paper_size;
        $this->orientation = $orientation;
	}

	// --------------------------------------------------------------------


	/**
	 * Create PDF
	 *
	 * @access	public
	 * @return	void
	 */
	function create($mode = 'download')
	{

   		if (is_null($this->html)) {
			show_error("HTML is not set");
		}

   		if (is_null($this->path)) {
			show_error("Path is not set");
		}

   		if (is_null($this->paper_size)) {
			show_error("Paper size not set");
		}

		if (is_null($this->orientation)) {
			show_error("Orientation not set");
		}

		// Image type not found issue solved

	    $dompdf = new DOMPDF();
        //Load the DOMPDF libary
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $options->setIsFontSubsettingEnabled(true);

	    $dompdf->setOptions($options);//image issue last line
	    $dompdf->loadHtml($this->html, 'UTF-8');
        // dd($dompdf);
	    $dompdf->setPaper($this->paper_size, $this->orientation);
	    $dompdf->render();

	    if ($mode == 'save') {
         $this->CI->load->helper('file');
         if(write_file($this->path.$this->filename, $dompdf->output())) {
   		    	return $this->path.$this->filename;
   		    } else {
   				show_error("PDF could not be written to the path");
   		    }
     } elseif ($dompdf->stream($this->filename, array( 'Attachment'=>0 ))) {
         return TRUE;
     } else {
  				show_error("PDF could not be streamed");
  			}
	}

}

/* End of file Html2pdf.php */
