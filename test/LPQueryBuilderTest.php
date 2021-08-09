<?php

require_once('..\LPQueryBuilder.php');
require_once('..\SqlFormatter.php');
class_alias('LPQueryBuilder', 'QB');

class LPQueryBuilderTest {

	public function __construct() {
		
	}	

	public function selectAll() {
		$query = QB::table('GbUsuario')
			->join('GbCriador ON GbCriador.Id = GbUsuario.CriadoPor')
			->leftJoin('GbModificador ON GbModificador.Id = GbUsuario.ModifPor')
			->select(
				'GbUsuario.Id as id',
				'GbUsuario.Nome as nome',
				'GbUsuario.FuncionarioId as funcionarioId',
				'GbUsuario.CriadoEm as criadoEm',
				'GbUsuario.CriadoPor as criadorId',
				'GbCriador.Nome as criador',
				'GbUsuario.ModifEm as modificadoEm',
				'GbUsuario.ModifPor as modificadorId',
				'GbModificador.Nome as modificador',
				'GbUsuario.Ativo as ativo'
			)
			->where(
				'GbUsuario.Ativo = 1',
				"GbUsuario.Nome like '%ala%'"
			)
			->groupBy(
				'GbUsuario.Nome', 'GbUsuario.Ativo'
			)
			->orderBy(
				'GbUsuario.Nome', 'GbUsuario.Ativo ASC'
			)
			->paginate(10, 1);

		print SqlFormatter::format($query->sqlCount());
		print SqlFormatter::format($query->sql());
	}

	public function insert() {
		$query = QB::table('GbUsuario')
			->insert([
				'GbUsuario.Nome'=>'Fulano de Tal',
				'GbUsuario.Ativo'=> 1,
				'GbUsuario.CriadoPor'=>1,
				'GbUsuario.CriadoEm'=>date('Y-m-d H:i:s')
			]);
		print SqlFormatter::format($query->sql());
	}

	public function update() {
		$query = QB::table('GbUsuario')
			->where(
				'GbUsuario.Ativo = 1',
				"GbUsuario.Nome like '%ala%'"
			)
			->update([
				'GbUsuario.Nome'=>'Fulano de Tal',
				'GbUsuario.Ativo'=> 1,
				'GbUsuario.CriadoPor'=>1,
				'GbUsuario.CriadoEm'=>date('Y-m-d H:i:s')
			]);
		print SqlFormatter::format($query->sql());
	}
}

