<?php

namespace App\Http\Controllers;

use App\Models\Lote;   // Import the Lote model
use App\Models\Setor;  // Import the Setor model (needed for dropdown in form)
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // Import for better error handling if needed

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all Lotes and eager load their associated Setor
        $lotes = Lote::with('setor')->get();

        // Fetch all Setores to populate the dropdown in the creation/edit form
        $setores = Setor::all();

        // Return the view with the fetched data
        return view('lotes.index', compact('lotes', 'setores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data for Lote creation
            $request->validate([
                'setor_id' => 'required|exists:setores,id', // Ensure setor_id exists in the setores table
                'nome' => 'required|string|max:255',
                'preco' => 'required|numeric|min:0', // Price should be numeric and non-negative
                'quantidade' => 'required|integer|min:1', // Quantity should be integer and at least 1
                'data_inicio' => 'required|date', // Validate as a date
                'data_fim' => 'required|date|after_or_equal:data_inicio', // Validate as date and after or equal to start date
                'descricao' => 'nullable|string', // Description is optional
            ]);

            // Create a new Lote record with the validated data
            Lote::create($request->all());

            // Redirect back to the lote index with a success message
            return redirect()->route('lotes.index')
                             ->with('success', 'Lote criado com sucesso!');

        } catch (ValidationException $e) {
            // If validation fails, redirect back with errors
            return redirect()->back()
                             ->withErrors($e->errors())
                             ->withInput();
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao criar o lote: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        // Find the Lote by its ID and eager load its associated Setor
        // If not found, a 404 response will be automatically returned
        $lote = Lote::with('setor')->findOrFail($id);

        // Return the Lote model (with the loaded Setor) as JSON
        return response()->json($lote);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the Lote record to be updated
            $lote = Lote::findOrFail($id);

            // Validate the incoming request data for Lote update
            $request->validate([
                'setor_id' => 'required|exists:setores,id',
                'nome' => 'required|string|max:255',
                'preco' => 'required|numeric|min:0',
                'quantidade' => 'required|integer|min:1',
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after_or_equal:data_inicio',
                'descricao' => 'nullable|string',
            ]);

            // Update the Lote record with the validated data
            $lote->update($request->all());

            // Redirect back to the lote index with a success message
            return redirect()->route('lotes.index')
                             ->with('success', 'Lote atualizado com sucesso!');

        } catch (ValidationException $e) {
            // If validation fails, redirect back with errors
            return redirect()->back()
                             ->withErrors($e->errors())
                             ->withInput();
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao atualizar o lote: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Find the Lote record to be deleted
            $lote = Lote::findOrFail($id);

            // Delete the Lote record
            $lote->delete();

            // Redirect back to the lote index with a success message
            return redirect()->route('lotes.index')
                             ->with('success', 'Lote excluído com sucesso!');

        } catch (\Exception $e) {
            // Catch any errors during deletion (e.g., foreign key constraints)
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao excluir o lote: ' . $e->getMessage());
        }
    }
}