<?php

namespace App\Livewire\Admin\Calendar;

use Livewire\Component;

use App\Models\Calendar;
use App\Models\Track;
use App\Models\RaceCalendar;
use App\Models\Racing;
use App\Models\RacingBet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class CalendarCrud extends Component
{
    use WithPagination;

    public $dateAt;
    public $showModal = false;
    public $modalTitle = '';
    public $editingId = null;
    public $form = [
        'track_id' => '',
        'date_at' => '',
        'total_races' => 13,
        'is_active' => true
    ];

    protected $rules = [
        'form.track_id' => 'required|exists:tracks,id',
        'form.date_at' => 'required|date',
        'form.total_races' => 'required|integer|min:1|max:20',
        'form.is_active' => 'boolean'
    ];

    public function mount()
    {
        $this->dateAt = now()->format('Y-m-d');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $calendars = Calendar::with(['track'])
            ->orderBy('date_at', 'desc')
            ->orderBy('track_id', 'asc')
            ->paginate(10);

        $tracks = Track::whereIn('id', [4,8,9,18,22,23,25,26,27,30,32,38,52,55,59,60,61,68,76,82,83,89,90,91,103,105,116,119])
            ->get();

        return view('livewire.admin.calendar.calendar-crud', compact('calendars', 'tracks'));
    }

    public function createJornadas()
    {
        $this->validate([
            'dateAt' => 'required|date'
        ]);

        try {
            DB::transaction(function () {
                Calendar::where('date_at', $this->dateAt)->delete();

                //$tracks = Track::whereIn('id', [52,4,9,18,22,25,119,26,27,30,32,38,55,59,60,61,68,76,82,83,89,90,91,103,105,116,23,8])->get();
                $trackIds = [52,4,22,25,119,26,27,30,32,38,55,9,59,60,61,68,18,76,82,83,89,90,91,103,105,116,23,8];
                $tracks = Track::whereIn('id', $trackIds)
                    ->orderByRaw('FIELD(id, ' . implode(',', $trackIds) . ')')
                    ->get();

                foreach($tracks as $track) {
                    $calendar = Calendar::create([
                        'track_id' => $track->id,
                        'date_at' => $this->dateAt,
                        'total_races' => 13,
                        'is_active' => true,
                    ]);

                    RaceCalendar::create([
                        'calendar_id' => $calendar->id,
                        'race_current' => 1,
                        'user_id' => Auth::id() ?? 1
                    ]);

                    for ($b=1; $b <= $calendar->total_races ; $b++) { 
                        $race = Racing::create([
                            'calendar_id' => $calendar->id,
                            'race' => $b,
                            'total_horses' => 16,
                            'start_time' => $calendar->date_at->format('Y-m-d') . ' ' . now()->addMinutes($b * 10)->format('H:i'),
                            'distance' => rand(1000, 2000),
                            'status' => 'open',
                        ]);

                        for ($j=1; $j <= 3 ; $j++) { 
                            RacingBet::create([
                                'racing_id' => $race->id,
                                'bet_type_id' => $j,
                            ]);
                        }
                    }
                }
            });

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Jornadas creadas exitosamente!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al crear las jornadas: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        $this->reset('form', 'editingId');
        $this->form['date_at'] = $this->dateAt;
        $this->modalTitle = 'Crear Calendario';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $calendar = Calendar::findOrFail($id);
        $this->form = $calendar->toArray();
        $this->form['date_at'] = $calendar->date_at->format('Y-m-d');
        $this->editingId = $id;
        $this->modalTitle = 'Editar Calendario';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                $calendar = Calendar::findOrFail($this->editingId);
                $calendar->update($this->form);
                $message = 'Calendario actualizado exitosamente!';
            } else {
                Calendar::create($this->form);
                $message = 'Calendario creado exitosamente!';
            }

            $this->showModal = false;
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $message
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al guardar: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            Calendar::findOrFail($id)->delete();
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Calendario eliminado exitosamente!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $calendar = Calendar::findOrFail($id);
            $calendar->update(['is_active' => !$calendar->is_active]);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Estado actualizado exitosamente!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ]);
        }
    }
}
