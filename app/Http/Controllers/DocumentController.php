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
    public function api()
    {
        $request_documents = Document::with('attachments')->where('public','!=',null)->where('status',null)->get();

        return response()->json($request_documents,200);
    }
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
        if((auth()->user()->role == "User"))
        {
            $documents = Document::where('department_id',auth()->user()->department_id)->get();
            $obsoletes = Obsolete::where('department_id',auth()->user()->department_id)->get();
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
    public function edit(Request $request,$id)
    {
        //

        $document = Document::findOrfail($id);
        $document->title = $request->title;
        $document->version = $request->revision;
        $document->save(['timestamps' => FALSE]);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
      
        
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
        ini_set('memory_limit', '-1');
        $attachment = DocumentAttachment::with('document')->findOrFail($id);
            $pdf = new \setasign\Fpdi\Fpdi();
            $newFile = str_replace(' ', '%20', $attachment->attachment);
          
                $fileContentData = file_get_contents(url($newFile));
                try {
                    
                    $pageCount = $pdf->setSourceFile(StreamReader::createByString($fileContentData));
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                            // $pdf->AddPage();
                            $pdf->setSourceFile(StreamReader::createByString($fileContentData));
                            $tplIdx = $pdf->importPage($pageNo);
                            $size = $pdf->getTemplateSize($tplIdx);
                            $pdf->AddPage('L', array($size[1],$size[0]));
                            // dd($size);
                            $pdf->useTemplate($tplIdx);
                            $pdf->SetFont('Arial');
                            $pdf->SetTextColor(1, 0, 0);
                            $pdf->SetXY(160, 5);
                            $pdf->SetFontSize(9);
                            if($pageNo == 1)
                            {
                                $pdf->Write(1, "Effective Date: ".date("m/d/Y",strtotime($attachment->document->effective_date))); 
                            }
                           
                            $pdf->Image('images/uncontrolled.png', 15, 100, 200, '', '', '', '', false, 300);
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
