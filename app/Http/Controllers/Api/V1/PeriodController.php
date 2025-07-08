<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Symptom;
use Carbon\Carbon;

class PeriodController extends Controller
{
    public function getSymptoms(Request $request)
    {
        $periods = Period::where('user_id', $request->user()->id)
            ->with('symptoms')
            ->orderBy('start_date', 'desc')
            ->get();

        $symptoms = Symptom::all();
        return response()->json($symptoms);
    }
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'duration' => 'required|integer',
            'symptoms' => 'array',
        ]);

        $period = Period::create([
            'user_id' => $request->user()->id,
            'start_date' => $request->start_date,
            'duration' => $request->duration,
            'next_prediction' => Carbon::parse($request->start_date)->addDays(28),
        ]);

        if ($request->filled('symptoms')) {
            $period->symptoms()->sync($request->symptoms);
        }
        return response()->json(['message' => 'Période enregistrée', 'data' => $period], 200);
    }

    public function index(Request $request)
    {
       
        $periods = Period::where('user_id', $request->user()->id)
            ->with('symptoms')
            ->orderBy('start_date', 'desc')
            ->get();
        return response()->json($periods);
    }

    public function predictCycles(Request $request)
    {
        $lastPeriod = Period::where('user_id', $request->user()->id)
            ->latest('start_date')
            ->first();

        if (!$lastPeriod) {
            return response()->json(['message' => 'Aucune période trouvée pour l’utilisateur.'], 404);
        }

        $predictions = [];
        $start = Carbon::parse($lastPeriod->start_date);
        $cycleLength = 28; // ou ajustable si tu veux le rendre dynamique
        $periodLength = $lastPeriod->duration;

        for ($i = 1; $i <= 3; $i++) {
            $nextStart = $start->copy()->addDays($i * $cycleLength);
            $nextEnd = $nextStart->copy()->addDays($periodLength - 1);

            $predictions[] = [
                'cycle_number' => $i,
                'start_date' => $nextStart->toDateString(),
                'end_date' => $nextEnd->toDateString(),
            ];
        }

        return response()->json([
            'message' => 'Prévisions pour les 3 prochains cycles',
            'predictions' => $predictions
        ]);
    }

}
