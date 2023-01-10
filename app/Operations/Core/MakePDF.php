<?php


namespace App\Operations\Core;

use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Http\Response;
use LogicException;

/**
 * Class MakePDF
 * @package App\Operations\Internal
 */
class MakePDF
{
    public PDF    $pdf;
    public string $paperSize   = "A4";
    public string $orientation = 'portrait';
    public bool   $warnings    = false;
    public string $name        = "PDF";

    /**
     * MakePDF constructor.
     */
    public function __construct()
    {
        \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'logOutputFile' => storage_path()."logs/pdflog.htm"
        ]);
        $this->pdf = app('dompdf.wrapper');
    }


    /**
     * Enter the name of the PDF without extension
     * @param $name
     * @return $this
     */
    public function setName($name): MakePDF
    {
        $this->name = $name;
        if (!preg_match("/\.pdf/", $name))
        {
            $this->name .= ".pdf";
        }
        return $this;
    }

    /**
     * Override Paper Size
     * @param $size
     * @return $this
     */
    public function setPaperSize($size): MakePDF
    {
        $this->paperSize = $size;
        return $this;
    }

    /**
     * Override Orientation
     * @param $orientation
     * @return $this
     */
    public function setOrientation($orientation): MakePDF
    {
        $this->orientation = $orientation;
        return $this;
    }

    /**
     * Override Warnings
     * @param $warnings
     * @return $this
     */
    public function setWarnings($warnings): MakePDF
    {
        $this->warnings = $warnings;
        return $this;
    }

    /**
     * Stream to the browser from html loaded.
     * This is used for dynamically rendered content.
     * @param $data
     * @return Response
     */
    public function streamFromData($data)
    {
        try
        {
            return $this->pdf->loadHTML($data)
                ->setPaper($this->paperSize, $this->orientation)
                ->setWarnings($this->warnings)
                ->stream($this->name);
        } catch (Exception $e)
        {
            \Log::info($e->getMessage());
            throw new LogicException("There was a problem rendering the PDF. Please contact support." . $e->getFile() . " - " . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * Write the file. Don't forget to cleanup.
     * @param $data
     * @return string
     */
    public function saveFromData($data): string
    {
        if (!is_dir(storage_path() . "/pdf/"))
        {
            //Directory does not exist, so lets create it.
            mkdir(storage_path() . "/pdf/", 0777);
        }
        $path = storage_path() . "/pdf/" . $this->name;
        $short = "pdf/" . $this->name;
        if (file_exists($path)) @unlink($path); // Remove PDF in case it already exists.
        $this->pdf->loadHTML($data)
            ->setPaper($this->paperSize, $this->orientation)
            ->setWarnings($this->warnings)
            ->save($path);
        return $short;
    }

    /**
     * Remove a temporary PDF. Just use the name used previously when writing.
     */
    public function cleanup()
    {
        @unlink(storage_path() . "/pdf/$this->name");
    }
}
