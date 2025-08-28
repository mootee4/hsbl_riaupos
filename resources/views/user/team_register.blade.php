<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Tim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 2rem;
        }

        .register-form {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .register-form h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            font-weight: 500;
        }

        select,
        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button[type="submit"] {
            margin-top: 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            width: 100%;
            font-weight: 500;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    @if(session('success'))
    <div style="color: green; text-align:center; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
    @endif

    <div class="register-form">
        <h2>Pendaftaran Tim</h2>
        <form action="{{ route('user.team.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Sekolah</label>
                <select name="school_id" required>
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach ($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kompetisi</label>
                <select name="competition" required>
                    <option value="">-- Pilih Kompetisi --</option>
                    @foreach ($competitions as $comp)
                    <option value="{{ $comp }}">{{ $comp }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Season</label>
                <select name="season" required>
                    <option value="">-- Pilih Season --</option>
                    @foreach ($seasons as $season)
                    <option value="{{ $season }}">{{ $season }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Series</label>
                <select name="series" required>
                    <option value="">-- Pilih Series --</option>
                    @foreach ($series as $ser)
                    <option value="{{ $ser }}">{{ $ser }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kategori Tim</label>
                <select name="team_category" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($teamCategoryEnums as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Upload Surat Rekomendasi</label>
                <input type="file" name="recommendation_letter" accept=".pdf" required>
            </div>

            <div class="form-group">
                <label>Upload Bukti Pembayaran</label>
                <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png" required>
            </div>
            <div class="form-group">
                <label>Upload Bukti Langganan Koran</label>
                <input type="file" name="koran" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>
            <button type="submit">Daftarkan Tim</button>
        </form>
    </div>

</body>

</html>