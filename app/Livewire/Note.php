<?php

namespace App\Livewire;

use App\Models\Note as NoteModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use OpenAI\Client;
// use GuzzleHttp\Client;
use OpenAI;

class Note extends Component
{
    use WithFileUploads;

    public $exisitingNote = null;
    public ?NoteModel $note = null; // Curkrent note selected
    
    // New Note fields
    public $is_new_note = true;
    public $new_chart_image = null;
    public $new_title = "";
    public $new_note = "";
    public $new_summary = "";
    public $new_aiResponse = "";
    
    // Selected Note fields
    public $chart_image = null;
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
            'new_note' => 'nullable',
            'new_chart_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Handle image upload
        $chartImagePath = null;
        if ($this->new_chart_image) {
            $chartImagePath = $this->new_chart_image->store('chart_images', 'public');
        }

        Auth::user()->notes()->create([
            'title' => $validatedData["new_title"],
            'summary' => $validatedData["new_summary"],
            'note' => $validatedData["new_note"],
            'chart_image' => $chartImagePath
        ]);

        session()->flash('message', 'Note saved successfully!');
        
        $this->updateIsCreateNote(false);
        
        return redirect()->route('note');
    }

    // Delete selected note
    public function delete(){
        $note = NoteModel::find($this->note->id);
        
        // Delete image file if exists
        if ($note->chart_image) {
            \Storage::disk('public')->delete($note->chart_image);
        }
        
        $note->delete();
        session()->flash('message', 'Note deleted successfully!');
        
        return redirect()->route('note');
    }

    // Update selected note
    public function update(){
        $validatedData = $this->validate([
            'selected_title' => 'required|min:3',
            'selected_summary' => 'nullable',
            'selected_note' => 'nullable',
            'chart_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Handle image upload
        $chartImagePath = $this->note->chart_image;
        if ($this->chart_image && $this->chart_image !== $this->note->chart_image) {
            // Delete old image if exists
            if ($this->note->chart_image) {
                \Storage::disk('public')->delete($this->note->chart_image);
            }
            
            // Store new image
            $chartImagePath = $this->chart_image->store('chart_images', 'public');
        }

        $this->note->update([
            'title' => $validatedData["selected_title"],
            'summary' => $validatedData["selected_summary"],
            'note' => $validatedData["selected_note"],
            'chart_image' => $chartImagePath
        ]);

        session()->flash('message', 'Note updated successfully!');
        
        return redirect()->route('note');
    }

    // Generate summary by using openai
    public function generateSummary()
    {
        if(!$this->note){ // Generating summary for new note
            $this->validate([
                'new_title' => 'required|min:1',
                'new_note' => 'required|min:10',
                'new_chart_image' => 'nullable|image|max:5120', // 5MB max
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
                
                // Prepare messages for OpenAI
                $messages = [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes trading notes.'],
                    ['role' => 'user', 'content' => 'Please summarize this trading note by listing out possible improvements that can be considered: ' . $this->new_note]
                ];
                
                // If there's an image, add it to the message content
                if ($this->new_chart_image) {
                    // Store the image temporarily
                    $tempPath = $this->new_chart_image->store('temp', 'public');
                    $fullPath = \Storage::disk('public')->path($tempPath);
                    
                    // Encode the image to base64
                    $imageData = file_get_contents($fullPath);
                    $base64Image = base64_encode($imageData);
                    
                    // Add image to messages
                    $messages = [
                        ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes trading notes.'],
                        ['role' => 'user', 'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Please summarize this trading note by listing out possible improvements that can be considered. The chart image is also attached: ' . $this->new_note
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:image/jpeg;base64,{$base64Image}"
                                ]
                            ]
                        ]]
                    ];
                    
                    // Use GPT-4 Vision model if there's an image
                    $response = $client->chat()->create([
                        'model' => 'gpt-4-vision-preview',
                        'messages' => $messages,
                        'max_tokens' => 350
                    ]);
                    
                    // Clean up temporary file
                    \Storage::disk('public')->delete($tempPath);
                } else {
                    // Use regular GPT model if no image
                    $response = $client->chat()->create([
                        'model' => 'gpt-3.5-turbo',
                        'messages' => $messages,
                        'max_tokens' => 350
                    ]);
                }
    
                $this->new_summary = $response->choices[0]->message->content;
                $this->dispatch('summary-generated');
            } catch (\Exception $e) {
                session()->flash('error', 'Failed to generate summary: ' . $e->getMessage());
            }
        } else { // Re-Generating summary for existing note
            $this->validate([
                'selected_title' => 'required|min:1',
                'selected_note' => 'required|min:10',
                'chart_image' => 'nullable|image|max:5120', // 5MB max
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
                
                // Prepare messages for OpenAI
                $messages = [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes trading notes.'],
                    ['role' => 'user', 'content' => 'Please summarize this trading note by listing out possible improvements that can be considered: ' . $this->selected_note]
                ];
                
                // If there's an image, add it to the message content
                if ($this->chart_image) {
                    // If chart_image is a file upload instance
                    if (!is_string($this->chart_image)) {
                        // Store the image temporarily
                        $tempPath = $this->chart_image->store('temp', 'public');
                        $fullPath = \Storage::disk('public')->path($tempPath);
                        
                        // Encode the image to base64
                        $imageData = file_get_contents($fullPath);
                        $base64Image = base64_encode($imageData);
                        
                        // Clean up after use
                        $cleanupTempFile = true;
                    } else {
                        // It's a path to an existing image
                        $fullPath = \Storage::disk('public')->path($this->chart_image);
                        $imageData = file_get_contents($fullPath);
                        $base64Image = base64_encode($imageData);
                        $cleanupTempFile = false;
                        $tempPath = null;
                    }
                    
                    // Add image to messages
                    $messages = [
                        ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes trading notes.'],
                        ['role' => 'user', 'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Please summarize this trading note by listing out possible improvements that can be considered. The chart image is also attached: ' . $this->selected_note
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:image/jpeg;base64,{$base64Image}"
                                ]
                            ]
                        ]]
                    ];
                    
                    // Use GPT-4 Vision model if there's an image
                    $response = $client->chat()->create([
                        'model' => 'gpt-4-vision-preview',
                        'messages' => $messages,
                        'max_tokens' => 350
                    ]);
                    
                    // Clean up temporary file if needed
                    if ($cleanupTempFile && $tempPath) {
                        \Storage::disk('public')->delete($tempPath);
                    }
                } else {
                    // Use regular GPT model if no image
                    $response = $client->chat()->create([
                        'model' => 'gpt-3.5-turbo',
                        'messages' => $messages,
                        'max_tokens' => 350
                    ]);
                }
    
                $this->selected_summary = $response->choices[0]->message->content;
                $this->dispatch('summary-generated');
            } catch (\Exception $e) {
                session()->flash('error', 'Failed to generate summary: ' . $e->getMessage());
            }
        }
    }

    public function loadNote(NoteModel $note)
    {
        $this->note = $note;
        $this->updateIsCreateNote(false); // Set is_new_note to false

        // Update selected note fields
        $this->selected_title = $this->note->title;
        $this->selected_summary = $this->note->summary;
        $this->selected_note = $this->note->note;
        $this->selected_aiResponse = $this->note->ai_response;
        $this->chart_image = $this->note->chart_image;
    }
}
