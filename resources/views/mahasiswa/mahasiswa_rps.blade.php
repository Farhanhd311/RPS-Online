@extends('layouts.app')

@section('content')
<div x-data='rpsPage({{ json_encode($semesters) }})' class="space-y-6 mt-4">
    @php($userRole = auth()->user()->role ?? 'mahasiswa')
    <div class="flex items-center gap-3">
        <a href="{{ route('fakultas.program.detail', ['code'=>$code, 'slug'=>'program-studi-s1-sistem-informasi']) }}?role={{ $userRole }}" class="inline-flex items-center">
            <img src="{{ asset('images/back.png') }}" alt="Back" class="h-7 w-7 object-contain" />
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-700">S1 Sistem Informasi</h1>
    </div>
	<div class="bg-white rounded-xl p-6 shadow-sm ring-1 ring-slate-100">
		<p class="font-semibold text-slate-800">Pilih Semester</p>
		<div class="mt-3 flex items-center gap-3">
			<div class="relative w-full">
				<select x-model.number="selected" class="w-full appearance-none rounded-xl border border-emerald-300 pl-4 pr-10 py-2.5 text-emerald-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm">
					<template x-for="s in all" :key="s.value">
						<option :value="s.value" x-text="s.label"></option>
					</template>
				</select>
				<span class="pointer-events-none i-heroicons-chevron-down-20-solid absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600"></span>
			</div>
			<button class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold shadow hover:bg-emerald-700" @click="apply()">PILIH</button>
		</div>

		<div class="mt-8 space-y-3">
			<template x-for="c in courses" :key="c.kode || c.name">
				<div class="border border-slate-200 rounded-xl px-6 py-4 hover:border-emerald-400 hover:shadow-md transition-all bg-gradient-to-r from-white to-slate-50">
					<div class="flex items-center justify-between">
						<div class="flex-1">
							<div class="flex items-center gap-3 mb-1.5">
								<span x-show="c.kode" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full" x-text="c.kode"></span>
								<span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
									<span x-text="c.sks || 0"></span> SKS
								</span>
							</div>
							<p class="text-slate-800 font-medium text-base" x-text="c.name"></p>
						</div>
						<div class="flex items-center gap-4 ml-6">
							<template x-if="c.has_rps && c.rps_id">
								<div class="flex items-center gap-4">
									<a :href="`/mahasiswa/{{ $code }}/rps/${c.rps_id}/view`" target="_blank" class="inline-flex items-center gap-2 text-sm text-emerald-700 hover:text-emerald-800 font-medium transition-colors">
										<span class="i-heroicons-eye text-lg"></span>
										<span>Lihat PDF</span>
									</a>
									<a :href="`/mahasiswa/{{ $code }}/rps/${c.rps_id}/download`" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-700 font-medium transition-colors">
										<span class="i-heroicons-arrow-down-tray text-lg"></span>
										<span>Unduh PDF</span>
									</a>
									<button @click="openForum(c.rps_id, c.name)" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
										<span class="i-heroicons-chat-bubble-left-right text-lg"></span>
										<span>Komentar</span>
									</button>
								</div>
							</template>
							<template x-if="!c.has_rps">
								<div class="text-sm text-slate-400 italic">
									RPS belum tersedia
								</div>
							</template>
						</div>
					</div>
				</div>
			</template>
			<div x-show="!courses.length" class="text-center py-12 text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-300">
				<span class="i-heroicons-academic-cap text-4xl text-slate-300 block mb-2"></span>
				<p class="text-sm font-medium">Tidak ada mata kuliah untuk semester ini.</p>
			</div>
		</div>
	</div>

	<!-- Forum Diskusi Modal -->
	<div x-show="forumOpen" @click.self="closeForum()" x-transition class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
		<div @click.stop class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col animate-in fade-in zoom-in-95 duration-200">
			<!-- Modal Header -->
			<div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5 flex items-center justify-between">
				<div class="flex-1">
					<h2 class="text-2xl font-bold text-white">Forum Diskusi</h2>
					<p class="text-emerald-100 text-sm mt-1" x-text="forumMataKuliah"></p>
				</div>
				<button @click="closeForum()" class="text-white hover:bg-emerald-500 p-2 rounded-lg transition-colors ml-4 flex-shrink-0">
					<span class="i-heroicons-x-mark text-2xl"></span>
				</button>
			</div>

			<!-- Modal Body -->
			<div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50">
				<!-- Comments List -->
				<template x-for="comment in forumComments" :key="comment.suggestion_id">
					<div class="bg-white rounded-xl p-4 border border-slate-200 hover:border-emerald-300 hover:shadow-md transition-all">
						<div class="flex items-start justify-between mb-3">
							<div class="flex-1">
								<div class="flex items-center gap-2 mb-1">
									<p class="font-semibold text-slate-800" x-text="comment.username"></p>
									<span class="text-xs px-2.5 py-1 rounded-full font-medium" :class="comment.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : comment.status === 'reviewed' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'" x-text="comment.status"></span>
								</div>
								<p class="text-xs text-slate-500" x-text="formatDate(comment.created_at)"></p>
							</div>
						</div>
						<p class="text-slate-700 text-sm leading-relaxed" x-text="comment.saran"></p>
					</div>
				</template>

				<div x-show="!forumComments.length" class="text-center py-12">
					<div class="text-slate-400 mb-2">
						<span class="i-heroicons-chat-bubble-left-right text-5xl block mb-3"></span>
					</div>
					<p class="text-slate-600 font-medium">Belum ada komentar</p>
					<p class="text-slate-500 text-sm">Jadilah yang pertama memberikan masukan!</p>
				</div>
			</div>

			<!-- Modal Footer -->
			<div class="border-t border-slate-200 p-6 bg-white">
				<form @submit.prevent="submitComment()" class="space-y-4">
					<div>
						<label class="block text-sm font-medium text-slate-700 mb-2">Tulis Masukan atau Saran</label>
						<textarea x-model="newComment" placeholder="Bagikan masukan Anda untuk meningkatkan kualitas RPS..." rows="3" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none transition-colors"></textarea>
						<p class="text-xs text-slate-500 mt-1">Minimal 5 karakter</p>
					</div>
					<div class="flex gap-3 justify-end">
						<button type="button" @click="closeForum()" class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors inline-flex items-center gap-2">
							<span class="i-heroicons-arrow-left text-lg"></span>
							Batal
						</button>
						<button type="submit" :disabled="!newComment.trim() || forumLoading" class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
							<span x-show="!forumLoading" class="i-heroicons-paper-airplane text-lg"></span>
							<span x-show="forumLoading" class="i-heroicons-arrow-path text-lg animate-spin"></span>
							<span x-show="!forumLoading">Kirim Masukan</span>
							<span x-show="forumLoading">Mengirim...</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</div>

<script>
function rpsPage(semesters) {
	return {
		all: semesters,
		selected: semesters[0]?.value ?? 1,
		courses: semesters[0]?.courses ?? [],
		forumOpen: false,
		forumRpsId: null,
		forumMataKuliah: '',
		forumComments: [],
		newComment: '',
		forumLoading: false,
		
		apply() {
			const s = this.all.find(s => s.value === this.selected);
			this.courses = s ? s.courses : [];
		},

		openForum(rpsId, mataKuliah) {
			this.forumRpsId = rpsId;
			this.forumMataKuliah = mataKuliah;
			this.forumOpen = true;
			this.newComment = '';
			this.loadComments();
		},

		closeForum() {
			this.forumOpen = false;
			this.forumRpsId = null;
			this.forumMataKuliah = '';
			this.forumComments = [];
			this.newComment = '';
		},

		async loadComments() {
			try {
				const response = await fetch(`/api/rps/${this.forumRpsId}/suggestions`);
				const data = await response.json();
				this.forumComments = data.suggestions || [];
			} catch (error) {
				console.error('Error loading comments:', error);
			}
		},

		async submitComment() {
			if (!this.newComment.trim()) return;

			this.forumLoading = true;
			try {
				const response = await fetch(`/api/rps/${this.forumRpsId}/suggestions`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
					},
					body: JSON.stringify({
						saran: this.newComment
					})
				});

				if (response.ok) {
					this.newComment = '';
					await this.loadComments();
				} else {
					alert('Gagal mengirim masukan');
				}
			} catch (error) {
				console.error('Error submitting comment:', error);
				alert('Terjadi kesalahan');
			} finally {
				this.forumLoading = false;
			}
		},

		formatDate(dateString) {
			const date = new Date(dateString);
			return date.toLocaleDateString('id-ID', {
				year: 'numeric',
				month: 'long',
				day: 'numeric',
				hour: '2-digit',
				minute: '2-digit'
			});
		}
	};
}
</script>
@endsection

