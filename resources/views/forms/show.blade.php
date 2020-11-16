@extends('layouts.master')

@section('meta.title', $form->name)

@section('content')
    <div class="flex flex-col min-h-full bg-grey-200">
        <div class="flex justify-center items-center bg-blue-500-to-right-purple-500 px-4 pt-5 pb-4">
            <img
                src="/images/logo.svg"
                alt="Formtrap"
                class="block w-32"
            >
        </div>

        <div class="flex-grow w-full px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <h1 class="title mb-4">{{ $form->name }}</h1>
                
                @if(session('success'))
                    <div class="bg-green-500 text-white text-center shadow px-4 py-8 mb-6 md:px-8">
                        Thank you, your submission has been successfully received.
                    </div>
                @endif

                <div class="bg-white shadow px-4 py-8 mb-6 md:px-8">
                    <form action="{{ route('forms.submit', $form->hashed_id) }}" method="post">
                        @foreach($fields as $field)
                            <div class="field">
                                <label for="{{ $field->name }}" class="label">
                                    {{ $field->label }}
                                    @if($field->required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @include('forms.fields.' . strtolower($field->type->name))

                                @if($errors->has($field->name))
                                    <div class="error-message">
                                        {{ $errors->first($field->name) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="field pt-4">
                            <div class="control text-center">
                                <button class="button primary w-full sm:w-2/5">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="bg-grey-300 text-sm py-4">
            <div class="container text-center mx-auto px-8 md:flex md:items-center md:justify-between">
                <div></div>

                <div class="text-grey-400">
                    &copy; 2019 FormTrap
                </div>
            </div>
        </footer>
    </div>
@endsection
