<?php

namespace App\Livewire;

use App\Models\Note as NoteModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use OpenAI\Client;
// use GuzzleHttp\Client;
use OpenAI;

class Note extends Component
{
    public $exisitingNote = null;
    public ?NoteModel $note = null; // Curkrent note selected
    
    // New Note fields
    public $is_new_note = true;
    public $new_title = "";
    public $new_note = "";
    public $new_summary = "";
    public $new_aiResponse = "";

    // Selected Note fields
    public $selected_title = "";
    public $selected_note = "";
    public $selected_summary = "";

    public function updateIsCreateNote($is_new_note)
    {
        $this->is_new_note = $is_new_note;
    }
    
    public function mount()
    {
        $this->exisitingNote = auth()->user()->notes;

        // Default to creating a new note
        // $this->updateIsCreateNote(true);
    }

    public function render()
    {
        return view('livewire.note')
            ->layout('layouts.app');
    }

    // Save new note
    public function save()
    {
        $validatedData = $this->validate([
            'new_title' => 'required|min:3',
            'new_summary' => 'nullable',
            'new_note' => 'nullable'
        ]);

        Auth::user()->notes()->create([
            'title' => $validatedData["new_title"],
            'summary' => $validatedData["new_summary"],
            'note' => $validatedData["new_note"]
        ]);

        session()->flash('message', 'Note saved successfully!');
        
        $this->updateIsCreateNote(false);
        
        return redirect()->route('note');
    }

    // Delete selected note
    public function delete(){
        $res = NoteModel::find($this->note->id)->delete();
        session()->flash('message', 'Note deleted successfully!');
        
        return redirect()->route('note');
    }

    // Update selected note
    public function update(){
        // Updating note
        $validatedData = $this->validate([
            'selected_title' => 'required|min:3',
            // 'selected_summary' => 'nullable',
            // 'selected_note' => 'nullable'
        ]);

        $this->note->update([
            'title' => $validatedData["selected_title"],
            'summary' => $validatedData["selected_summary"],
            'note' => $validatedData["selected_note"]
        ]);

        session()->flash('message', 'Note updated successfully!');
        
        return redirect()->route('note');
    }

    // Generate summary by using openai
    public function generateSummary()
    {
        // dd($this->new_note); 
        $this->validate([
            'new_note' => 'required|min:10'
        ]);

        try {

            // Create HTTP client with SSL configuration
            $httpClient = new \GuzzleHttp\Client([
                'verify' => storage_path('cacert.pem')
            ]);

            // Create OpenAI client with custom HTTP client
            $client = \OpenAI::factory()
                ->withHttpClient($httpClient)
                ->withApiKey(config('app.openai_api_key'))
                ->make();
            
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes trading notes.'],
                    ['role' => 'user', 'content' => 'Please summarize this trading note: ' . $this->new_note],
                ],
                'max_tokens' => 150
            ]);

            $this->new_summary = $response->choices[0]->message->content;
            // dd($this->new_summary);
            $this->dispatch('summary-generated');
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error', 'Failed to generate summary: ' . $e->getMessage());
        }
    }

    public function loadNote(NoteModel $note)
    {
        $this->note = $note;
        $this->updateIsCreateNote(false); // Set is_new_note to false
        // dd($this->note);

        // Update selected note fields
        $this->selected_title = $this->note->title;
        $this->selected_summary = $this->note->summary;
        $this->selected_note = $this->note->note;
        $this->selected_aiResponse = $this->note->ai_response;
    }

}
