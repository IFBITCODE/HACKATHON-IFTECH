<?php

namespace App\Http\Controllers;

use App\Models\Occurrence;
use Illuminate\Http\Request;

class OccurrenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Occurrence::query();

        if ($request->filled('location')) {
            $query->where(
                'location',
                'like',
                '%' . $request->location . '%'
            );
        }

        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->category
            );
        }

        if ($request->filled('severity')) {
            $query->where(
                'severity',
                $request->severity
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $occurrences = $query
            ->latest('occurred_at')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.occurrences.index',
            compact('occurrences')
        );
    }

    public function create()
    {
        return view(
            'admin.occurrences.create'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'required',
                'string'
            ],

            'location' => [
                'required',
                'string',
                'max:255'
            ],

            'category' => [
                'required',
                'string',
                'max:100'
            ],

            'severity' => [
                'required',
                'in:baixa,media,alta,critica'
            ],

            'status' => [
                'required',
                'in:aberta,em_atendimento,resolvida,cancelada'
            ],

            'occurred_at' => [
                'required',
                'date'
            ],

            'resolution_notes' => [
                'nullable',
                'string'
            ],
        ]);

        if (
            $validated['status'] === 'resolvida'
        ) {
            $validated['resolved_at'] = now();
        }

        Occurrence::create($validated);

        return redirect()
            ->route('admin.occurrences.index')
            ->with(
                'success',
                'Ocorrência registrada com sucesso.'
            );
    }

    public function show(Occurrence $occurrence)
    {
        return view(
            'admin.occurrences.show',
            compact('occurrence')
        );
    }

    public function edit(Occurrence $occurrence)
    {
        return view(
            'admin.occurrences.edit',
            compact('occurrence')
        );
    }

    public function update(
        Request $request,
        Occurrence $occurrence
    ) {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'required',
                'string'
            ],

            'location' => [
                'required',
                'string',
                'max:255'
            ],

            'category' => [
                'required',
                'string',
                'max:100'
            ],

            'severity' => [
                'required',
                'in:baixa,media,alta,critica'
            ],

            'status' => [
                'required',
                'in:aberta,em_atendimento,resolvida,cancelada'
            ],

            'occurred_at' => [
                'required',
                'date'
            ],

            'resolution_notes' => [
                'nullable',
                'string'
            ],
        ]);

        if (
            $validated['status'] === 'resolvida'
            && !$occurrence->resolved_at
        ) {
            $validated['resolved_at'] = now();
        }

        if (
            $validated['status'] !== 'resolvida'
        ) {
            $validated['resolved_at'] = null;
        }

        $occurrence->update($validated);

        return redirect()
            ->route('admin.occurrences.index')
            ->with(
                'success',
                'Ocorrência atualizada com sucesso.'
            );
    }

    public function destroy(Occurrence $occurrence)
    {
        $occurrence->delete();

        return redirect()
            ->route('admin.occurrences.index')
            ->with(
                'success',
                'Ocorrência removida com sucesso.'
            );
    }
}