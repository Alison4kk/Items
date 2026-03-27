# Deploy da Documentação para GitHub Pages

Aqui estão os passos para ativar o deploy automático da documentação.

## 1. Ativar GitHub Pages no repositório

1. Vá para **Settings** do repositório no GitHub
2. Vá para **Pages** (no menu lateral esquerdo)
3. Em "Build and deployment", selecione:
   - **Source**: "GitHub Actions"
4. Salve as configurações

## 2. Fazer o primeiro commit

```bash
git add .github/workflows/deploy-docs.yml
git add docs/.vitepress/config.js
git commit -m "Configure GitHub Pages deployment for documentation"
git push origin main
```

## 3. Acompanhar o deployment

1. Vá para **Actions** no repositório
2. Veja o workflow `Deploy Docs to GitHub Pages` rodando
3. Quando ficar verde ✅, a documentação está live!

## 4. Acessar a documentação

A documentação estará disponível em:
```
https://alison4kk.github.io/Items/
```

## Próximas vezes

Toda vez que você fizer push para `main` e modificar a pasta `docs/`, o workflow automaticamente:
1. ✅ Instala as dependências
2. ✅ Faz o build da documentação
3. ✅ Deploy para GitHub Pages

## Se usar domínio customizado

Se quiser usar um domínio customizado (ex: `docs.items.com`):

1. Edite `.github/workflows/deploy-docs.yml`
2. Descomente o `cname` e adicione seu domínio:
```yaml
      - name: Deploy to GitHub Pages
        uses: peaceiris/actions-gh-pages@v3
        with:
          github_token: ${{ secrets.GITHUB_TOKEN }}
          publish_dir: ./docs/.vitepress/dist
          cname: docs.items.com
```
3. Configure o DNS do seu domínio para apontar para GitHub Pages
