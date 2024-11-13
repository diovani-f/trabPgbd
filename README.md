Telas: 
 - [ ] Tela da Grade Semanal
 - [ ] Cadastro de curso 
 - [ ] Cadastro de disciplina 
 - [ ] Cadastro de professores
 - [ ] Cadastro de coordenadores 

Sinc: 
- Front manda uma requisição com os dados:
	-  data inicio da semana*
	- data final da semana*
	- curso*
	- id da disciplina
	- Nome da disciplina
	*obrigatorios pra busca.
	
- Back faz a busca e retorna os dados em um json
	- Dia da semana
	- Nome da disciplina
	- Professor
	- (Talvez mais alguma coisa)
	
- Front desenha na tela com js  

Banco de dados:
- Criar banco de dados
- Normalizar banco de dados
- Criar triggers...
- Criar modelo ER pra apresentação

Back:
-  (Fiz uma request com o js simples so pra ver se os dados estaam passando correto pro php e vice-versa)
- ~~- Criar conexão com o banco~~
- ~~Qrys e arquivo de consulta de disciplina | da tela principal~~ (falta arrumar quando o banco tiver pronto)
-  Qrys e arquivo de inserção/edição/exclusão/visualização professor
- Qrys e arquivo de inserção/edição/exclusão/visualizaçãode disciplinas
- Qrys e arquivo de inserção/edição/exclusão/visualizaçãoprofessor
- Qrys e arquivo de inserção/edição/exclusão/visualizaçãocurso
