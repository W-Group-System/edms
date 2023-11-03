<?php

namespace App\Http\Controllers;
use App\Document;
use App\Obsolete;
use App\Department;
use App\DocumentType;
use App\DocumentAttachment;
use App\Company;
use Illuminate\Http\Request;
use \setasign\Fpdi\PdfParser\StreamReader;
use \setasign\Fpdi\PdfParser\CrossReference;
use Illuminate\Support\Facades\Redirect;

use RealRashid\SweetAlert\Facades\Alert;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       
        $documents = Document::get();
        $obsoletes = Obsolete::get();
        if(auth()->user()->role == "Document Control Officer")
        { 
   
            $documents = Document::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
            $obsoletes = Obsolete::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
                   
        }
        if(auth()->user()->role == "Documents and Records Controller")
        { 
   
            $documents = Document::where('department_id',auth()->user()->department_id)->get();
            $obsoletes = Obsolete::where('department_id',auth()->user()->department_id)->get();
                   
        }
        
        if((auth()->user()->role == "Department Head"))
        {
            $documents = Document::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
            $obsoletes = Obsolete::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
        }
        $departments = Department::get();
        $companies = Company::get();
        $document_types = DocumentType::orderBy('name','desc')->get();
        return view('documents',
        array(
            'documents' => $documents,
            'obsoletes' => $obsoletes,
            'departments' => $departments,
            'companies' => $companies,
            'document_types' => $document_types,
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());
        $document = new Document;
        $document->control_code = $request->control_code;
        $document->title = $request->title;
        $document->company_id = $request->company;
        $document->department_id = $request->department;
        $document->category = $request->document_type;
        $document->other_category = $request->other;
        $document->effective_date = $request->effective_date;
        $document->user_id = auth()->user()->id;
        $document->version = $request->version;
        $document->public = $request->public;
        $document->save();

        foreach($request->file('attachment') as $key => $file)
        {
            
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path() . '/document_attachments/', $name);
            $file_name = '/document_attachments/' . $name;

            $doc_attachment = new DocumentAttachment;
            $doc_attachment->document_id = $document->id;
            $doc_attachment->attachment = $file_name;
            $doc_attachment->type = $key;
            $doc_attachment->save();
            
        }
        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $document = Document::findOrfail($id);
        // dd($document);

        return view('view_document',
        array(
            'document' => $document,
            ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
      
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function showPDF($id)
    {
        $attachment = DocumentAttachment::findOrFail($id);
            $pdf = new \setasign\Fpdi\Fpdi();
            $newFile = str_replace(' ', '%20', $attachment->attachment);
          
                $fileContentData = file_get_contents(url($newFile));
                try {
                    
                    $pageCount = $pdf->setSourceFile(StreamReader::createByString($fileContentData));
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $pdf->AddPage();
                            $pdf->setSourceFile(StreamReader::createByString($fileContentData));
                            $tplIdx = $pdf->importPage($pageNo);
                            $pdf->useTemplate($tplIdx);
                            $pdf->SetFont('Helvetica');
                            $pdf->SetTextColor(255, 0, 0);
                            $pdf->SetXY(10, 250);
                            // $this->Rect(5, 5, 200, 287, 'D');
                            $pdf->Image('images/stamp.png', 50, 250, 50, '', '', '', '', false, 300);
                    }
                    $pdf->Output();
                }
                catch ( \Exception $e )
                {
                    return Redirect::to(url($newFile));
                }
              
           
        
      
    }

    public function audit()
    {
        $documents = Document::get();
        $obsoletes = Obsolete::get();

        return view('documents',
        array(
            'documents' => $documents,
            'obsoletes' => $obsoletes,
            )
        );
    }
}
