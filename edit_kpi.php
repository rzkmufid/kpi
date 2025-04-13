<?php
include 'config/db.php';
include 'includes/header.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM kpi WHERE id = ?");
$stmt->execute([$id]);
$kpi = $stmt->fetch();
?>
<div class="container mt-4">
  <h4>Edit KPI</h4>
  <form action="update_kpi.php" method="POST">
    <input type="hidden" name="id" value="<?= $kpi['id'] ?>">
    <div class="mb-3">
      <label for="jabatan" class="form-label">Jabatan</label>
      <div class="input-group">
        <select name="jabatan_id" class="form-select" id="jabatan_id" required>
          <option value="" disabled selected>-- Pilih Jabatan --</option>
          <?php
          $jabatan = $pdo->query("SELECT * FROM jabatan")->fetchAll();
          foreach ($jabatan as $j) {
            $sel = $j['id'] == $kpi['jabatan_id'] ? 'selected' : '';
            echo "<option value='{$j['id']}' $sel>{$j['nama_jabatan']}</option>";
          }
          ?>
        </select>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahJabatan">Tambah Jabatan</button>
      </div>
    </div>
    <div class="mb-3"><label>KPI</label><input type="text" name="kpi" class="form-control" value="<?= $kpi['kpi'] ?>" placeholder="Masukkan KPI" required></div>
    <div class="mb-3"><label>Jenis Hasil</label><input type="text" name="jenis_hasil" class="form-control" value="<?= $kpi['jenis_hasil'] ?>" placeholder="Masukkan Jenis Hasil"></div>
    <div class="mb-3">
      <label>Jumlah Dokumen</label>
      <div class="input-group">
        <input type="number" name="jumlah_dokumen" class="form-control" value="<?= $kpi['jumlah_dokumen'] ?>" placeholder="Masukkan Jumlah Dokumen" required>
        <input type="text" name="satuan_dokumen" class="form-control" value="<?= $kpi['satuan_dokumen'] ?>" placeholder="Masukkan Satuan">
      </div>
    </div>
    <div class="mb-3"><label>Bobot (%)</label><input type="number" step="0.1" name="bobot" class="form-control" id="bobot" value="<?= $kpi['bobot'] ?>" readonly placeholder="Bobot"></div>
    <div class="mb-3"><label>Target (%)</label><input type="number" step="0.01" name="target" class="form-control" value="100" readonly placeholder="Target"></div>
    <div class="mb-3"><label>Pencapaian (%)</label><input type="number" step="0.01" name="pencapaian" class="form-control" id="pencapaian" value="<?= $kpi['pencapaian'] ?>" placeholder="Masukkan Pencapaian"></div>
    <div class="mb-3"><label>Skor</label><input type="number" step="0.01" name="skor" class="form-control" id="skor" value="<?= $kpi['skor'] ?>" readonly placeholder="Skor"></div>
    <button class="btn btn-success">Simpan Perubahan</button>
  </form>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const selectJabatan = document.getElementById('jabatan_id');
    const pencapaianInput = document.getElementById('pencapaian');
    const bobotInput = document.getElementById('bobot');
    const skorInput = document.getElementById('skor');

    const calculateSkor = () => {
      const bobot = parseFloat(bobotInput.value) || 0;
      const pencapaian = parseFloat(pencapaianInput.value) || 0;
      const skor = (pencapaian / bobot) * 100;
      skorInput.value = isFinite(skor) ? skor.toFixed(2) : 0;
    };

    selectJabatan.addEventListener('change', async function() {
      const id_jabatan = this.value;
      if (id_jabatan) {
        try {
          const response = await fetch(`get_bobot.php?id_jabatan=${id_jabatan}`);
          if (!response.ok) throw new Error('Network response was not ok');
          const data = await response.json();
          const bobot = data.jumlah || 0;
          bobotInput.value = typeof bobot === 'number' ? bobot.toFixed(2) : bobot;
          calculateSkor();
        } catch (error) {
          console.error('Fetch error:', error);
          bobotInput.value = 0;
          calculateSkor();
        }
      } else {
        bobotInput.value = 0;
        calculateSkor();
      }
    });

    pencapaianInput.addEventListener('input', calculateSkor);
  });
</script>

<!-- Modal Tambah Jabatan -->
<div class="modal fade" id="modalTambahJabatan" tabindex="-1">
  <div class="modal-dialog">
    <form action="update_jabatan.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Jabatan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label>Nama Jabatan</label>
        <input type="text" name="nama_jabatan" class="form-control" placeholder="Masukkan Nama Jabatan" required>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

