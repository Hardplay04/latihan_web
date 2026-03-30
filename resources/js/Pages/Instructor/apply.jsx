import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Apply({ auth, currentRole, instructorStatus, application, reviewNote }) {
  const { data, setData, post, processing, errors } = useForm({
    institution: application?.institution ?? '',
    position: application?.position ?? '',
    proof_url: application?.proof_url ?? '',
    message: application?.message ?? '',
  });

  const submit = (e) => {
    e.preventDefault();
    post(route('instructor.apply.store'));
  };

  const statusBadge = () => {
    const map = {
      none: 'Belum Mengajukan',
      pending: 'Menunggu Persetujuan',
      approved: 'Disetujui',
      rejected: 'Ditolak',
    };
    return map[instructorStatus] ?? instructorStatus;
  };

  const canSubmit = !(currentRole === 'instructor' && instructorStatus === 'approved') && instructorStatus !== 'pending';

  return (
    <AuthenticatedLayout
      user={auth.user}
      header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Ajukan sebagai Guru/Dosen</h2>}
    >
      <Head title="Ajukan Guru/Dosen" />

      <div className="py-8">
        <div className="max-w-3xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white shadow-sm sm:rounded-lg p-6">
            <div className="mb-6">
              <div className="flex items-center justify-between">
                <p className="text-sm text-gray-600">Role saat ini: <span className="font-semibold">{currentRole}</span></p>
                <span className="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                  Status: {statusBadge()}
                </span>
              </div>

              {instructorStatus === 'rejected' && reviewNote && (
                <div className="mt-3 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                  <div className="font-semibold mb-1">Catatan Admin:</div>
                  <div>{reviewNote}</div>
                </div>
              )}

              {instructorStatus === 'approved' && (
                <div className="mt-3 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
                  Akun Anda sudah disetujui sebagai Guru/Dosen.
                </div>
              )}

              {instructorStatus === 'pending' && (
                <div className="mt-3 p-3 rounded-lg bg-yellow-50 text-yellow-800 text-sm">
                  Pengajuan Anda sedang diproses Admin.
                </div>
              )}
            </div>

            <form onSubmit={submit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700">Institusi</label>
                <input
                  className="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                  value={data.institution}
                  onChange={(e) => setData('institution', e.target.value)}
                  disabled={!canSubmit}
                />
                {errors.institution && <p className="text-sm text-red-600 mt-1">{errors.institution}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700">Posisi</label>
                <input
                  className="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Guru / Dosen / Lecturer"
                  value={data.position}
                  onChange={(e) => setData('position', e.target.value)}
                  disabled={!canSubmit}
                />
                {errors.position && <p className="text-sm text-red-600 mt-1">{errors.position}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700">Link Bukti (opsional)</label>
                <input
                  className="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="https://..."
                  value={data.proof_url}
                  onChange={(e) => setData('proof_url', e.target.value)}
                  disabled={!canSubmit}
                />
                {errors.proof_url && <p className="text-sm text-red-600 mt-1">{errors.proof_url}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700">Pesan Pengajuan</label>
                <textarea
                  className="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                  rows="5"
                  value={data.message}
                  onChange={(e) => setData('message', e.target.value)}
                  disabled={!canSubmit}
                />
                {errors.message && <p className="text-sm text-red-600 mt-1">{errors.message}</p>}
              </div>

              <div className="pt-2 flex items-center gap-3">
                <button
                  type="submit"
                  disabled={!canSubmit || processing}
                  className="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 disabled:opacity-50"
                >
                  {processing ? 'Mengirim...' : 'Kirim Pengajuan'}
                </button>

                {!canSubmit && (
                  <span className="text-sm text-gray-500">
                    Anda tidak bisa mengirim pengajuan saat status pending/approved.
                  </span>
                )}
              </div>

              <div className="text-xs text-gray-500 pt-3">
                Catatan: User yang register otomatis menjadi Student. Hanya Guru/Dosen yang disetujui Admin yang dapat membuat kelas & tugas.
              </div>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
