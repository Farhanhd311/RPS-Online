@extends('layouts.app')

@section('content')
<div x-data='rpsPage({{ json_encode($semesters) }})' class="space-y-6 mt-4">
    @php($userRole = auth()->user()->role ?? 'reviewer')
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
							<template x-if="c.rps_id && c.rps_status === 'approved'">
								<a :href="`/reviewer/{{ $code }}/rps/${c.rps_id}/detail`" class="inline-flex items-center gap-2 text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
									<span class="i-heroicons-check-circle text-lg"></span>
									<span>Accepted</span>
								</a>
							</template>
							<template x-if="c.rps_id && c.rps_status !== 'approved'">
								<a :href="`/reviewer/{{ $code }}/rps/${c.rps_id}/detail`" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
									<span class="i-heroicons-clipboard-document-check text-lg"></span>
									<span>Review</span>
								</a>
							</template>
							<button @click="openComments(c.rps_id, c.name)" class="inline-flex items-center gap-2 text-sm text-purple-600 hover:text-purple-700 font-medium transition-colors">
								<span class="i-heroicons-chat-bubble-left-right text-lg"></span>
								<span>Komentar</span>
							</button>
							<a href="#" @click.prevent="download(c)" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-700 font-medium transition-colors">
								<span class="i-heroicons-arrow-down-tray text-lg"></span>
								<span>Unduh</span>
							</a>
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

	<!-- Modal preview sederhana -->
	<div x-show="preview" x-transition.opacity class="fixed inset-0 bg-black/40 grid place-items-center z-40 p-4">
		<div class="bg-white rounded-xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.outside="preview=null">
			<div class="flex items-start justify-between mb-4">
				<div class="flex-1">
					<div class="flex items-center gap-3 mb-2">
						<span x-show="preview?.kode" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full" x-text="preview?.kode"></span>
						<span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
							<span x-text="preview?.sks || 0"></span> SKS
						</span>
						<span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
							Semester <span x-text="preview?.semester || '-'"></span>
						</span>
					</div>
					<p class="text-lg font-semibold text-slate-800" x-text="preview?.name"></p>
				</div>
				<button @click="preview=null" class="ml-4 text-slate-400 hover:text-slate-600 transition-colors">
					<span class="i-heroicons-x-mark text-xl"></span>
				</button>
			</div>
			<div class="border-t border-slate-200 pt-4">
				<p class="text-sm text-slate-600">Pratinjau RPS (mock). Tempatkan embed PDF atau konten di sini.</p>
			</div>
			<div class="mt-6 flex justify-end gap-3">
				<button class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors" @click="preview=null">Tutup</button>
				<button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">Buka Detail</button>
			</div>
		</div>
	</div>

	<!-- Modal Komentar -->
	<div x-show="commentsOpen" @click.self="closeComments()" x-transition class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
		<div @click.stop class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">
			<!-- Modal Header -->
			<div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5 flex items-center justify-between">
				<div class="flex-1">
					<h2 class="text-2xl font-bold text-white">Komentar Mahasiswa</h2>
					<p class="text-purple-100 text-sm mt-1" x-text="commentsMataKuliah"></p>
				</div>
				<button @click="closeComments()" class="text-white hover:bg-purple-500 p-2 rounded-lg transition-colors ml-4 flex-shrink-0">
					<span class="i-heroicons-x-mark text-2xl"></span>
				</button>
			</div>

			<!-- Modal Body -->
			<div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50">
				<template x-for="comment in comments" :key="comment.suggestion_id">
					<div class="bg-white rounded-xl p-4 border border-slate-200 hover:border-purple-300 hover:shadow-md transition-all">
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

				<div x-show="!comments.length" class="text-center py-12">
					<div class="text-slate-400 mb-2">
						<span class="i-heroicons-chat-bubble-left-right text-5xl block mb-3"></span>
					</div>
					<p class="text-slate-600 font-medium">Belum ada komentar</p>
					<p class="text-slate-500 text-sm">Mahasiswa belum memberikan masukan untuk RPS ini</p>
				</div>
			</div>

			<!-- Modal Footer -->
			<div class="border-t border-slate-200 p-6 bg-white">
				<button @click="closeComments()" class="w-full px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors inline-flex items-center justify-center gap-2">
					<span class="i-heroicons-arrow-left text-lg"></span>
					Tutup
				</button>
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
		preview: null,
		commentsOpen: false,
		commentsRpsId: null,
		commentsMataKuliah: '',
		comments: [],
		
		apply() {
			const s = this.all.find(s => s.value === this.selected);
			this.courses = s ? s.courses : [];
		},
		
		view(course) { this.preview = course; },
		
		download(course) { alert('Unduh: ' + course.name); },
		
		openComments(rpsId, mataKuliah) {
			this.commentsRpsId = rpsId;
			this.commentsMataKuliah = mataKuliah;
			this.commentsOpen = true;
			this.loadComments();
		},

		closeComments() {
			this.commentsOpen = false;
			this.commentsRpsId = null;
			this.commentsMataKuliah = '';
			this.comments = [];
		},

		async loadComments() {
			try {
				const response = await fetch(`/api/rps/${this.commentsRpsId}/suggestions`);
				const data = await response.json();
				this.comments = data.suggestions || [];
			} catch (error) {
				console.error('Error loading comments:', error);
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

