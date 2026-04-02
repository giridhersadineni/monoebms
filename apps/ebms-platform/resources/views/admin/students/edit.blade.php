@extends('layouts.admin')
@section('title', 'Edit ' . $student->name)

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.students.index') }}"
               class="text-slate-400 hover:text-slate-600 text-sm">Students</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.students.show', $student->hall_ticket) }}"
               class="text-slate-400 hover:text-slate-600 text-sm">{{ $student->hall_ticket }}</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-600 text-sm">Edit</span>
        </div>
        <h1 class="text-xl font-semibold text-slate-800">Edit Student</h1>
    </div>

    <form method="POST"
          action="{{ route('admin.students.update', $student->id) }}"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Personal Details --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-2">Personal Details</h2>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="name" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Father's Name</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="father_name" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Mother's Name</label>
                    <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="mother_name" />
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Gender</label>
                    <select name="gender"
                            class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                        <option value="">—</option>
                        @foreach(['M' => 'Male', 'F' => 'Female', 'O' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ old('gender', $student->gender) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-form-error field="gender" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Date of Birth</label>
                    <input type="date" name="dob"
                           value="{{ old('dob', $student->dob?->format('Y-m-d')) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="dob" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Aadhaar</label>
                    <input type="text" name="aadhaar" value="{{ old('aadhaar', $student->aadhaar) }}"
                           maxlength="12" placeholder="12-digit number"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                                  placeholder:text-slate-400">
                    <x-form-error field="aadhaar" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Caste</label>
                    <input type="text" name="caste" value="{{ old('caste', $student->caste) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="caste" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Sub-Caste</label>
                    <input type="text" name="sub_caste" value="{{ old('sub_caste', $student->sub_caste) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="sub_caste" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="email" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="phone" />
                </div>
            </div>
        </div>

        {{-- Academic Details --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-2">Academic Details</h2>
            <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded px-3 py-2">
                These fields affect which papers are shown during enrollment. Change with care.
            </p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Course <span class="text-red-500">*</span></label>
                    <select name="course" id="course_select" required onchange="filterGroups(this.value)"
                            class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                        @foreach($courses as $c)
                            <option value="{{ $c->code }}"
                                    {{ old('course', $student->course) === $c->code ? 'selected' : '' }}>
                                {{ $c->code }} — {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-form-error field="course" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Course Name</label>
                    <input type="text" name="course_name" value="{{ old('course_name', $student->course_name) }}"
                           placeholder="e.g. Bachelor of Arts"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                                  placeholder:text-slate-400">
                    <x-form-error field="course_name" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Group Code</label>
                    <select name="group_code" id="group_select"
                            class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                        <option value="">— None —</option>
                        @foreach($groups as $g)
                            <option value="{{ $g->code }}"
                                    data-course="{{ $g->course->code ?? '' }}"
                                    {{ old('group_code', $student->group_code) === $g->code ? 'selected' : '' }}>
                                {{ $g->code }} — {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-form-error field="group_code" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Medium <span class="text-red-500">*</span></label>
                    <select name="medium" required
                            class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                        @foreach(['TM' => 'Telugu Medium', 'EM' => 'English Medium', 'BM' => 'Bilingual Medium'] as $val => $label)
                            <option value="{{ $val }}" {{ old('medium', $student->medium) === $val ? 'selected' : '' }}>{{ $val }} — {{ $label }}</option>
                        @endforeach
                    </select>
                    <x-form-error field="medium" />
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Scheme <span class="text-red-500">*</span></label>
                    <input type="number" name="scheme" value="{{ old('scheme', $student->scheme) }}"
                           min="2000" required
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="scheme" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Semester <span class="text-red-500">*</span></label>
                    <input type="number" name="semester" value="{{ old('semester', $student->semester) }}"
                           min="1" max="12" required
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="semester" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Admission Year</label>
                    <input type="number" name="admission_year" value="{{ old('admission_year', $student->admission_year) }}"
                           min="2000" max="2100"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="admission_year" />
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-2">Address</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Address Line 1</label>
                    <input type="text" name="address" value="{{ old('address', $student->address) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="address" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Address Line 2</label>
                    <input type="text" name="address2" value="{{ old('address2', $student->address2) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="address2" />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Mandal</label>
                    <input type="text" name="mandal" value="{{ old('mandal', $student->mandal) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="mandal" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city', $student->city) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="city" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">State</label>
                    <input type="text" name="state" value="{{ old('state', $student->state) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="state" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $student->pincode) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="pincode" />
                </div>
            </div>
        </div>

        {{-- Other / IDs --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-2">Other</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">DOST ID</label>
                    <input type="text" name="dost_id" value="{{ old('dost_id', $student->dost_id) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="dost_id" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">APAAR ID</label>
                    <input type="text" name="apaar_id" value="{{ old('apaar_id', $student->apaar_id) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="apaar_id" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">SSC Hall Ticket</label>
                    <input type="text" name="ssc_hall_ticket" value="{{ old('ssc_hall_ticket', $student->ssc_hall_ticket) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="ssc_hall_ticket" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Inter Hall Ticket</label>
                    <input type="text" name="inter_hall_ticket" value="{{ old('inter_hall_ticket', $student->inter_hall_ticket) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="inter_hall_ticket" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Challenged Quota</label>
                    <input type="text" name="challenged_quota" value="{{ old('challenged_quota', $student->challenged_quota) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="challenged_quota" />
                </div>
                <div class="flex items-end pb-1">
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $student->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="text-sm text-slate-700 font-medium">Account active</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                Save Changes
            </button>
            <a href="{{ route('admin.students.show', $student->hall_ticket) }}"
               class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</a>
        </div>
    </form>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
function filterGroups(courseCode) {
    const select = document.getElementById('group_select');
    Array.from(select.options).forEach(opt => {
        if (opt.value === '') return;
        opt.hidden = opt.dataset.course !== courseCode;
    });
    // Reset selection if current group doesn't belong to the new course
    const selected = select.options[select.selectedIndex];
    if (selected && selected.value && selected.dataset.course !== courseCode) {
        select.value = '';
    }
}
// Run on page load to sync the dropdown
filterGroups(document.getElementById('course_select').value);
</script>
@endsection
