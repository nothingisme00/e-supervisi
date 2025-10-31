@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Progress Bar -->
            <div class="progress mb-4" style="height: 25px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%">
                    Tahap 2: Proses Pembelajaran
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="supervisiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="evaluasi-tab" 
                            onclick="window.location.href='{{ route('guru.supervisi.evaluasi', $supervisi->id) }}'">
                        <i class="bi bi-check-circle-fill text-success"></i> Lembar Evaluasi Diri
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="proses-tab" data-bs-toggle="tab" 
                            data-bs-target="#proses" type="button">
                        <i class="bi bi-play-circle"></i> Proses
                    </button>
                </li>
            </ul>

            <!-- Form Content -->
            <form id="prosesForm">
                @csrf
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Link Pembelajaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">
                                Link Video Pembelajaran <span class="text-danger">*</span>
                            </label>
                            <input type="url" class="form-control" name="link_video" required
                                   placeholder="https://youtube.com/... atau https://drive.google.com/..."
                                   value="{{ $proses->link_video ?? '' }}">
                            <small class="text-muted">Masukkan link YouTube atau Google Drive</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                Link Meeting (Opsional)
                            </label>
                            <input type="url" class="form-control" name="link_meeting"
                                   placeholder="https://meet.google.com/... atau https://zoom.us/..."
                                   value="{{ $proses->link_meeting ?? '' }}">
                            <small class="text-muted">Masukkan link Google Meet atau Zoom jika ada</small>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Refleksi Pembelajaran</h5>
                    </div>
                    <div class="card-body">
                        @foreach($refleksiQuestions as $field => $question)
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $loop->iteration }}. {{ $question }} <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" name="{{ $field }}" rows="3" required
                                          placeholder="Minimal 10 karakter...">{{ $proses->$field ?? '' }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" 
                            onclick="window.location.href='{{ route('guru.supervisi.evaluasi', $supervisi->id) }}'">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>
                    <div>
                        <button type="button" class="btn btn-info" id="saveButton">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send"></i> Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 48px;"></i>
                <h4 class="mt-3">Supervisi Berhasil Disubmit!</h4>
                <p>Supervisi Anda telah berhasil disubmit dan akan segera direview oleh Kepala Sekolah.</p>
                <button type="button" class="btn btn-primary" 
                        onclick="window.location.href='{{ route('guru.home') }}'">
                    Kembali ke Beranda
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const supervisiId = {{ $supervisi->id }};

// Save button
document.getElementById('saveButton').addEventListener('click', async () => {
    const formData = new FormData(document.getElementById('prosesForm'));
    
    try {
        const response = await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Data berhasil disimpan!');
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

// Submit form
document.getElementById('prosesForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (!confirm('Apakah Anda yakin ingin mensubmit supervisi ini? Setelah disubmit, data tidak dapat diubah.')) {
        return;
    }
    
    // First save the data
    const formData = new FormData(e.target);
    
    try {
        // Save data
        const saveResponse = await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            body: formData
        });
        
        const saveResult = await saveResponse.json();
        
        if (saveResult.success) {
            // Then submit
            const submitResponse = await fetch(`/guru/supervisi/${supervisiId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const submitResult = await submitResponse.json();
            
            if (submitResult.success) {
                new bootstrap.Modal(document.getElementById('successModal')).show();
            }
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

// Auto-save every 30 seconds
setInterval(async () => {
    const formData = new FormData(document.getElementById('prosesForm'));
    
    try {
        await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            body: formData
        });
        console.log('Auto-saved');
    } catch (error) {
        console.error('Auto-save failed:', error);
    }
}, 30000);
</script>
@endsection