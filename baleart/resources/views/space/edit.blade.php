<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Espacio: ') }}  {{ $space->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <form action="{{ route('spaceCRUD.update', ['spaceCRUD' => $space->id ]) }}" method="post">
                        @csrf  <!-- Security Token -->
                        @method('PUT') 
                        <div class="mb-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="mt-1 block w-full" style="@error('name') border-color:RED; @enderror" value="{{ $space->name }}" name="name" />
                            @error('name')
                                <div>{{$message}}</div>
                            @enderror   
                        </div>
                        <div class="mb-3">
                            <label for="regNumber">Número de Registro</label>
                            <input type="text" class="mt-1 block w-full" style="@error('regNumber') border-color:RED; @enderror" value="{{$space->regNumber}}" name="regNumber" />
                            @error('regNumber')
                                <div>{{$message}}</div>
                            @enderror                
                        </div>
                        <div class="mb-3">
                            <label for="space_type_id" class="form-label">Tipo de Espacio</label>
                            <select name="space_type_id" class="mt-1 block w-full">
                                @foreach ($spaceTypes as $id => $name)
                                    <option value="{{ $id }}" {{ $space->space_type_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modality_id[]" class="form-label">Modalidades</label>
                            <select name="modality_id[]" class="mt-1 block w-full" style="@error('modality_id') border-color:RED; @enderror" multiple>
                                @foreach ($modalities as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, $space->modalities->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('modality_id')
                                <div>{{$message}}</div>
                            @enderror   
                        </div>
                        <div class="mb-3">
                            <label for="service_id[]" class="form-label">Servicios</label>
                            <select name="service_id[]" class="mt-1 block w-full" style="@error('service_id') border-color:RED; @enderror" multiple>
                                @foreach ($services as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, $space->services->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div>{{$message}}</div>
                            @enderror   
                        </div>
                        <div class="mb-3">
                            <label for="observation_CA">Descripción en Catalán</label>
                            <textarea id="editor_CA" style="@error('observation_CA') border-color:RED; @enderror" name="observation_CA" minlength="5" maxlength="5000" class="mt-1 block w-full">{{$space->observation_CA}}</textarea>
                            @error('observation_CA')
                                <div>{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="observation_ES">Descripción en Español</label>
                            <textarea id="editor_ES" style="@error('observation_ES') border-color:RED; @enderror" name="observation_ES" minlength="5" maxlength="5000" class="mt-1 block w-full">{{$space->observation_ES}}</textarea>
                            @error('observation_ES')
                                <div>{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="observation_EN">Descripción en Inglés</label>
                            <textarea id="editor_EN" style="@error('observation_EN') border-color:RED; @enderror" name="observation_EN" minlength="5" maxlength="5000" class="mt-1 block w-full">{{$space->observation_EN}}</textarea>
                            @error('observation_EN')
                                <div>{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address_id" class="form-label">Dirección</label>
                            <select name="address_id" class="mt-1 block w-full">
                                @foreach ($addresses as $id => $name)
                                    <option value="{{$id}}" {{ $space->address_id == $id ? 'selected' : '' }}>{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="accessType" class="form-label">Accesibilidad</label>
                            <select name="accessType" class="mt-1 block w-full">
                                <option value="s" {{ $space->accessType == 's' ? 'selected' : '' }}>Sí</option>
                                <option value="p" {{ $space->accessType == 'p' ? 'selected' : '' }}>Parcialmente accesible</option>
                                <option value="n" {{ $space->accessType == 'n' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="mt-1 block w-full" style="@error('email') border-color:RED; @enderror" value="{{ $space->email }}" name="email" />
                            @error('email')
                                <div>{{$message}}</div>
                            @enderror                
                        </div>
                        <div class="mb-3">
                            <label for="phone">Número de Teléfono</label>
                            <input type="text" class="mt-1 block w-full" style="@error('phone') border-color:RED; @enderror" value="{{ $space->phone }}" name="phone" />
                            @error('phone')
                                <div>{{$message}}</div>
                            @enderror                
                        </div>
                        <div class="mb-3">
                            <label for="website">Página Web</label>
                            <input type="text" class="mt-1 block w-full" style="@error('website') border-color:RED; @enderror" value="{{ $space->website }}" name="website" />
                            @error('website')
                                <div>{{$message}}</div>
                            @enderror                
                        </div>
                        @if ($puntuacióMitjana !== null)
                            <div class="mb-3">
                                <label for="puntuacióMitjana">Puntuación Media</label>
                                <input type="number" class="mt-1 block w-full" value="{{ $puntuacióMitjana }}" min="0" max="5" step="0.01" name="puntuacióMitjana" />
                            </div>
                        @endif
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Script para CKEditor -->
    <script>
        const {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph
        } = CKEDITOR;

        ClassicEditor
            .create( document.querySelector( '#editor_CA' ), {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzI0OTU5OTksImp0aSI6IjgwMDljYWUzLTVmOGMtNGI4OC05ODQ3LTVjOTRkYmRmNGY4NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMWM0NzY3YjEifQ.y52vPG3drra_F8PmjebRJLkgxHrX3waM_KtpNWJmVkXyoDhNxHPwcRz-hKiLoxRXxkAfeOt91k2zx2LXINbZIQ',
                plugins: [ Essentials, Bold, Italic, Font, Paragraph],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|'
                ]
            } )
            .then( /* ... */ )
            .catch( error => {
                console.error( error );
            });
        ClassicEditor
            .create( document.querySelector( '#editor_ES' ), {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzI0OTU5OTksImp0aSI6IjgwMDljYWUzLTVmOGMtNGI4OC05ODQ3LTVjOTRkYmRmNGY4NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMWM0NzY3YjEifQ.y52vPG3drra_F8PmjebRJLkgxHrX3waM_KtpNWJmVkXyoDhNxHPwcRz-hKiLoxRXxkAfeOt91k2zx2LXINbZIQ',
                plugins: [ Essentials, Bold, Italic, Font, Paragraph],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|'
                ]
            } )
            .then( /* ... */ )
            .catch( error => {
                console.error( error );
            });
        ClassicEditor
            .create( document.querySelector( '#editor_EN' ), {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzI0OTU5OTksImp0aSI6IjgwMDljYWUzLTVmOGMtNGI4OC05ODQ3LTVjOTRkYmRmNGY4NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMWM0NzY3YjEifQ.y52vPG3drra_F8PmjebRJLkgxHrX3waM_KtpNWJmVkXyoDhNxHPwcRz-hKiLoxRXxkAfeOt91k2zx2LXINbZIQ',
                plugins: [ Essentials, Bold, Italic, Font, Paragraph],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|'
                ]
            } )
            .then( /* ... */ )
            .catch( error => {
                console.error( error );
            });
    </script>
</x-app-layout>