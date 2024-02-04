<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Contatos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="APP_URL" content="{{ env('APP_URL') }}">

    <style>
        .loader-overlay {
            position: fixed; /* Posicionamento fixo na tela */
            top: 0;
            left: 0;
            width: 100%; /* Cobrir toda a largura */
            height: 100%; /* Cobrir toda a altura */
            background: rgba(255, 255, 255, 0.7) /* Cor de fundo com transparência */
            url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><radialGradient id="a7" cx=".66" fx=".66" cy=".3125" fy=".3125" gradientTransform="scale(1.5)"><stop offset="0" stop-color="%23000000"></stop><stop offset=".3" stop-color="%23000000" stop-opacity=".9"></stop><stop offset=".6" stop-color="%23000000" stop-opacity=".6"></stop><stop offset=".8" stop-color="%23000000" stop-opacity=".3"></stop><stop offset="1" stop-color="%23000000" stop-opacity="0"></stop></radialGradient><circle transform-origin="center" fill="none" stroke="url(%23a7)" stroke-width="15" stroke-linecap="round" stroke-dasharray="200 1000" stroke-dashoffset="0" cx="100" cy="100" r="70"><animateTransform type="rotate" attributeName="transform" calcMode="spline" dur="2" values="360;0" keyTimes="0;1" keySplines="0 0 1 1" repeatCount="indefinite"></animateTransform></circle><circle transform-origin="center" fill="none" opacity=".2" stroke="%23000000" stroke-width="15" stroke-linecap="round" cx="100" cy="100" r="70"></circle></svg>') center/10% no-repeat;
            z-index: 9999; /* Garantir que esteja acima de outros conteúdos */
            display: flex; /* Usar flexbox para alinhamento */
            justify-content: center; /* Centralizar horizontalmente */
            align-items: center; /* Centralizar verticalmente */
        }

    </style>

</head>
<body class="bg-white">
<div class="loader-overlay" id="loader-overlay" style="display: none;"></div>
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Lista de Contatos</h1>

    <div class="flex justify-between mb-4">
        <div class="flex space-x-4 flex-wrap">
            <form class="flex space-x-4" id="search-form">
                <input type="text" class="border-2 border-gray-300 p-2 h-10" placeholder="Digite para buscar ...">
                <button class="bg-white-500 text-black py-2 px-4 h-10 rounded  border-2 border-gray-300 align-center" type="submit">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
            </form>
        </div>
            <button class="bg-black text-white py-2 px-4 rounded mb-4" id="create-contact">
                <i class="fas fa-plus-circle"></i>
                Novo Contato
            </button>

    </div>

    <table id="contacts-table" class="min-w-full border-collapse">
        <thead>
        <tr>
            <th class="py-2 px-4 font-medium text-left border-b-2">Nome</th>
            <th class="py-2 px-4 font-medium text-left border-b-2">Número</th>
            <th class="py-2 px-4 font-medium text-left border-b-2">E-mail</th>
            <th class="py-2 px-4 font-medium text-left border-b-2">Ações</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="py-2 px-4">Nome do Contato</td>
            <td class="py-2 px-4">Número do Contato</td>
            <td class="py-2 px-4">E-mail do Contato</td>
            <td class="py-2 px-4">
                <button class="bg-white text-white py-1 px-2 rounded border-2 border-gray-600">
                    <i class="fas fa-eye text-gray-600"></i>
                </button>
                <button class="bg-white text-white py-1 px-2 rounded border-2 border-gray-600">
                    <i class="fas fa-edit text-gray-600"></i>
                </button>
                <button class="bg-white text-white py-1 px-2 rounded border-2 border-gray-600">
                    <i class="fas fa-trash text-gray-600"></i>
                </button>
            </td>
        </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-center">
                    <nav class="flex justify-center pagination">
                        <a href="#" class="text-black py-2 px-4 rounded border-2 border-gray-600 mx-1">1</a>
                        <a href="#" class="text-black py-2 px-4 rounded border-2 border-gray-600 mx-1">2</a>
                        <a href="#" class="text-black py-2 px-4 rounded border-2 border-gray-600 mx-1">3</a>
                    </nav>
                </td>
            </tr>
        </tfoot>
    </table>
</div>


<!-- Modal para Ver/Editar Contato -->
<div id="edit-modal" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay absolute inset-0 bg-gray-800 opacity-50"></div>
    <div class="modal-container bg-white w-96 mx-auto rounded-lg shadow-lg p-4 z-50">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold mb-4" id="modal-title">Detalhes do Contato</h2>
            <button id="close-modal" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Campos para visualização e edição do contato -->
        <form id="edit-contact-form" class="space-y-4">
            <div class="mb-4 w-full align-center">
                <label for="name" class="block text-sm font-medium text-gray-700 ">Nome</label>
                <input type="text" id="name" name="name" class="border-2 border-gray-300 p-2 h-10 w-full">
                <div id="name-error" class="text-red-500"></div>
            </div>
            <div class="mb-4">
                <label for="contact" class="block text-sm font-medium text-gray-700">Número</label>
                <input type="text" id="contact" name="contact" class="border-2 border-gray-300 p-2 h-10 w-full">
                <div id="contact-error" class="text-red-500"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="text" id="email" name="email" class="border-2 border-gray-300 p-2 h-10 w-full">
                <div id="email-error" class="text-red-500"></div>
            </div>
            <div class="flex justify-end">
                <button type="submit" id="save-contact" class="bg-black text-white py-2 px-4 rounded">Salvar</button>
                <button type="button" id="cancel-modal" class="bg-white text-black py-2 px-4 rounded ml-2 border-2 border-black">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<!-- Diálogo de Confirmação para Deletar -->
<div id="confirm-dialog" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay absolute inset-0 bg-gray-800 opacity-50"></div>
    <div class="modal-container bg-white w-96 mx-auto rounded-lg shadow-lg p-4 z-50">
        <form id="delete-contact-form" class="space-y-4">
            <p class="text-gray-700 text-xl font-normal mb-4">Tem certeza de que deseja deletar este contato <span id="contact-name" class="font-semibold "></span> ?</p>
            <input type="hidden" id="id_delete" name="id_delete">
            <div class="flex justify-end">
                <button type="submit" id="confirm-delete" class="bg-red-500 text-white py-2 px-4 rounded mx-2">Deletar</button>
                <button id="cancel-delete" class="bg-gray-400 text-white py-2 px-4 rounded mx-2">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal de Cadastro -->
<div id="create-modal" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay absolute inset-0 bg-gray-800 opacity-50"></div>
    <div class="modal-container bg-white w-96 mx-auto rounded-lg shadow-lg p-4 z-50">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold mb-4">Novo Contato</h2>
            <button id="close-create-modal" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="create-contact-form" class="space-y-4">
            <input type="hidden" id="id" name="id">
            <div class="mb-4 w-full align-center">
                <label for="name" class="block text-sm font-medium text-gray-700 ">Nome</label>
                <input type="text" id="name_create" name="name" class="border-2 border-gray-300 p-2 h-10 w-full">
                <div id="name-error-create" class="text-red-500"></div>
            </div>
            <div class="mb-4">
                <label for="contact" class="block text-sm font-medium text-gray-700">Número</label>
                <input type="text" id="contact_create" name="contact" class="border-2 border-gray-300 p-2 h-10 w-full">
                <div id="contact-error-create" class="text-red-500"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="text" id="email_create" name="email" class="border-2 border-gray-300 p-2 h-10 w-full">
                <div id="email-error-create" class="text-red-500"></div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-black text-white py-2 px-4 rounded">Salvar</button>
                <button id="cancel-create-modal" class="bg-white text-black py-2 px-4 rounded ml-2 border-2 border-black">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function () {
        const host = window.location.origin;

        function populateTable(pageUrl = host + '/api/contacts') {

            $('#loader-overlay').show();

            $.ajax({
                url: pageUrl,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    $('#contacts-table tbody').empty();


                    $.each(response.data, function (index, contact) {

                        var row = '<tr>' +
                            '<td class="py-2 px-4">' + contact.name + '</td>' +
                            '<td class="py-2 px-4">' + contact.contact + '</td>' +
                            '<td class="py-2 px-4">' + contact.email + '</td>' +
                            '<td class="py-2 px-4 space-x-2 flex justify-start">' +
                                '<button data-id="' + contact.id + '" class="bg-white text-white py-1 px-2 rounded border-2 border-black view-contact">' +
                                    '<i class="fas fa-eye text-black"></i>' +
                                '</button>' +
                                '<button data-id="' + contact.id + '" class="bg-white text-white py-1 px-2 rounded border-2 border-black edit-contact">' +
                                    '<i class="fas fa-edit text-black"></i>' +
                                '</button>' +
                                '<button data-id="' + contact.id + '" class="bg-white text-white py-1 px-2 rounded border-2 border-black confirm-delete-contact">' +
                                    '<i class="fas fa-trash text-black"></i>' +
                                '</button>' +
                            '</tr>';
                        $('#contacts-table tbody').append(row);


                        updatePagination(response.meta);
                    });

                    if (response.data.length === 0) {
                        var row = '<tr>' +
                            '<td colspan="4" class="text-center">Nenhum contato encontrado</td>' +
                            '</tr>';
                        $('#contacts-table tbody').append(row);

                        $('.pagination').empty();
                    }

                    $('#loader-overlay').hide();
                },
                error: function () {
                    alert('Erro ao buscar os contatos da API');
                }
            });

            if (pageUrl === '/api/contacts') {
                $('#search-form').find('input').val('');
            }
        }

        function updatePagination(meta) {
            const paginationContainer = $('.pagination');
            paginationContainer.empty();

            let paginationHtml = '';

            meta.links.forEach(link => {
                if(link.url) {
                    const APP_URL = $('meta[name="APP_URL"]').attr('content');
                    const HOST = window.location.origin;
                    const newUrl = link.url.replace(APP_URL, HOST);
                    const isActive = link.active ? 'bg-black text-white' : 'bg-white text-black';
                    paginationHtml += `<a href="#" class="${isActive} py-1 px-2 rounded border-2 border-black mx-1" data-url="${newUrl}">${link.label}</a>`;
                }
            });

            paginationContainer.html(paginationHtml);

            paginationContainer.find('a').on('click', function(e) {
                e.preventDefault();
                const pageUrl = $(this).data('url');
                populateTable(pageUrl);
            });
        }


        populateTable();

        $('#create-contact-form').submit(function (e) {
            e.preventDefault();
            $('#loader-overlay').show();

            var formData = {
                name: $('#name_create').val(),
                contact: $('#contact_create').val(),
                email: $('#email_create').val()
            };

            $('#name-error-create').empty();
            $('#contact-error-create').empty();
            $('#email-error-create').empty();

            $.ajax({
                url: '/api/contacts',
                method: 'POST',
                dataType: 'json',
                data: formData,
                success: function (data) {
                    $('#create-modal').addClass('hidden');
                    $('#create-contact-form')[0].reset();
                    $('#loader-overlay').hide();
                    populateTable();
                },
                error: function (error) {
                    if (error.responseJSON) {

                        if (error.responseJSON.name) {
                            $('#name-error-create').text(error.responseJSON.name[0]);
                            $('#name_create').addClass('border-red-500');
                        }
                        if (error.responseJSON.contact) {
                            $('#contact-error-create').text(error.responseJSON.contact[0]);
                            $('#contact_create').addClass('border-red-500');
                        }
                        if (error.responseJSON.email) {
                            $('#email-error-create').text(error.responseJSON.email[0]);
                            $('#email_create').addClass('border-red-500');
                        }
                    } else {
                        alert('Erro ao criar o contato: ' + error.responseJSON.message);
                    }
                    $('#loader-overlay').hide();
                }
            });
        });
        $('#delete-contact-form').submit(function (e) {
            e.preventDefault();

            let contactId = $('#id_delete').val();

            $('#loader-overlay').show();

            $.ajax({
                url: '/api/contacts/' + contactId,
                method: 'DELETE',
                dataType: 'json',
                success: function (response) {
                    $('#confirm-dialog').addClass('hidden');
                    populateTable();
                    $('#loader-overlay').hide();
                },
                error: function () {
                    alert('Erro ao deletar o contato');
                    $('#loader-overlay').hide();
                }
            });

            $('#confirm-dialog').addClass('hidden');
        });
        $('#edit-contact-form').submit(function (e) {
            e.preventDefault();

            $('#loader-overlay').show();

            var formData = {
                id: $('#id').val(),
                name: $('#name').val(),
                contact: $('#contact').val(),
                email: $('#email').val()
            };

            $.ajax({
                url: '/api/contacts/' + formData.id,
                method: 'PUT',
                dataType: 'json',
                data: formData,
                success: function (data) {
                    $('#edit-modal').addClass('hidden');

                    $('#name-error').empty();
                    $('#contact-error').empty();

                    $('#name').removeClass('border-red-500');
                    $('#contact').removeClass('border-red-500');
                    $('#email').removeClass('border-red-500');

                    populateTable();

                    $('#loader-overlay').hide();
                },
                error: function (error) {
                    if (error.responseJSON) {

                        if (error.responseJSON.name) {
                            $('#name-error').text(error.responseJSON.name[0]);
                            $('#name').addClass('border-red-500');
                        }
                        if (error.responseJSON.contact) {
                            $('#contact-error').text(error.responseJSON.contact[0]);
                            $('#contact').addClass('border-red-500');
                        }
                        if (error.responseJSON.email) {
                            $('#email-error').text(error.responseJSON.email[0]);
                            $('#email').addClass('border-red-500');
                        }
                    } else {
                        alert('Erro ao criar o contato: ' + error.responseJSON.message);
                    }
                    $('#loader-overlay').hide();
                }
            });
        });
        $('#search-form').submit(function (e) {
            e.preventDefault();
            var search = $(this).find('input').val();
            if (search === '') {
                populateTable();
                return;
            }
            populateTable('/api/contacts?search=' + search);
        });


        $(document).on('click', '.view-contact', function () {
            let contactId = $(this).data('id');
            $('#loader-overlay').show();

            $.ajax({
                url: '/api/contacts/' + contactId,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    let contactData = response.data;

                    $('#name').val(contactData.name).prop('disabled', true);
                    $('#contact').val(contactData.contact).prop('disabled', true);
                    $('#email').val(contactData.email).prop('disabled', true);

                    $('#edit-modal').removeClass('hidden');
                    $('#save-contact').addClass('hidden');

                    $('#modal-title').text('Detalhes do Contato');

                    $('#loader-overlay').hide();
                },
                error: function () {
                    alert('Erro ao buscar os detalhes do contato');
                    $('#loader-overlay').hide();
                }
            });
        });
        $(document).on('click', '.edit-contact', function () {
            var contactId = $(this).data('id');
            $('#loader-overlay').show();

            $.ajax({
                url: '/api/contacts/' + contactId,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    var contact = response.data;

                    $('#name').val(contact.name).prop('disabled', false);
                    $('#contact').val(contact.contact).prop('disabled', false);
                    $('#email').val(contact.email).prop('disabled', false);
                    $('#id').val(contact.id);

                    $('#edit-modal').removeClass('hidden');
                    $('#save-contact').removeClass('hidden');

                    $('#modal-title').text('Editar Contato');
                    $('#loader-overlay').hide();
                },

                error: function () {
                    alert('Erro ao buscar os detalhes do contato');
                    $('#loader-overlay').hide();
                }
            });


        });
        $(document).on('click', '.confirm-delete-contact', function () {
            let contactId = $(this).data('id');
            let contactName = $(this).closest('tr').find('td:first').text();

            $('#contact-name').text(contactName);
            $('#id_delete').val(contactId);
            $('#confirm-dialog').removeClass('hidden');
        });

        $('#create-contact').click(function () {
            $('#create-modal').removeClass('hidden');
        });

        $('#close-create-modal').click(function () {
            $('#create-contact-form')[0].reset();
            $('#create-modal').addClass('hidden');
        });

        $('#cancel-create-modal').click(function (e) {
            e.preventDefault();
            $('#create-contact-form')[0].reset();
            $('#create-modal').addClass('hidden');
        });

        $('#cancel-delete').click(function () {
            $('#confirm-dialog').addClass('hidden');
        });
        $('#close-modal').click(function () {
            $('#edit-modal').addClass('hidden');
        });
        $('#cancel-modal').click(function () {
            $('#edit-modal').addClass('hidden');
        });

    });

</script>
</body>
</html>
