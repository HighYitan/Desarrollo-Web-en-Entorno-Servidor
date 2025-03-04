<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Comentario de ') }} {{ $comment->user->name . " " . $comment->user->lastName . " en " . $comment->space->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <form action="{{ route('commentCRUD.update', ['commentCRUD' => $comment->id ]) }}" method="post">
                        @csrf  <!-- Security Token -->
                        @method('PUT') 
                        <div class="mb-3">
                            <label for="comment">Comentario</label>
                            <textarea id="comment" style="@error('comment') border-color:RED; @enderror" name="comment" minlength="5" maxlength="5000" class="mt-1 block w-full">{{$comment->comment}}</textarea>
                            @error('comment')
                                <div>{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="score">Puntuación</label>
                            <input type="number" class="mt-1 block w-full" value="{{ $comment->score }}" min="0" max="5" step="0.01" name="score" />
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado de Publicación</label>
                            <select name="status" class="mt-1 block w-full">
                                <option value="Y" {{ $comment->status == 'Y' ? 'selected' : '' }}>Publicado</option>
                                <option value="N" {{ $comment->status == 'N' ? 'selected' : '' }}>No Publicado</option>
                            </select>
                        </div>
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
            .create( document.querySelector( '#comment' ), {
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