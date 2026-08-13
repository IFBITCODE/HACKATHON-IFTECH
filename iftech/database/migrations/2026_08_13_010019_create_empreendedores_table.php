<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empreendedores', function (Blueprint $table) {
            $table->id();

            // Quem é o responsável pelo cadastro (usuário logado)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Dados básicos
            $table->string('nome_fantasia');
            $table->string('razao_social')->nullable();
            $table->string('cpf_cnpj')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // Contato
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();

            // Endereço
            $table->string('endereco')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Descrição do negócio
            $table->text('descricao')->nullable();
            $table->string('horario_funcionamento')->nullable();

            // Acessibilidade (edital seção 7)
            $table->boolean('acessivel')->default(false);
            $table->text('recursos_acessibilidade')->nullable();

            // Validação do cadastro (edital seção 9)
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado', 'suspenso'])
                  ->default('pendente');
            $table->text('motivo_rejeicao')->nullable();
            $table->timestamp('data_ultima_atualizacao')->nullable();

            // Selo de fornecedor validado (edital seção 9)
            $table->boolean('selo_validado')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empreendedores');
    }
};