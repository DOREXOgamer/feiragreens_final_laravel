@echo off
echo Instalando dependências PHP com Composer...
composer install

echo Instalando dependências Node.js com npm...
npm install

echo Compilando assets frontend...
npm run dev

echo Executando migrações do banco de dados...
php artisan migrate

echo Criando link simbólico para storage...
php artisan storage:link

echo Instalação concluída com sucesso!
pause
