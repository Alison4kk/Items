<?php

/**
 * @template T
 */
class Arr {

  /**
   * Filtra um array baseado em condições
   *
   * @template T
   * @param array<T> $aItens Array de itens a serem filtrados
   * @param callable(T): bool|array ...$xCondicoes Condições de filtro (função ou array [campo, operador, valor])
   * @return array<T> Array filtrado
   */
  public static function filtrar($aItens, ...$xCondicoes) {
    return array_values(array_filter($aItens, function($xItem) use ($xCondicoes) {
      foreach ($xCondicoes as $xCondicao) {
        if (!self::avaliarCondicao($xItem, $xCondicao)) {
          return false;
        }
      }
      return true;
    }));
  }

  /**
   * Ordena um array baseado em critérios
   *
   * @template T
   * @param array<T> $aItens Array de itens a serem ordenados
   * @param callable(T, T)|array: int|array ...$xCriterios Critérios de ordenação (função ou array [campo, direção])
   * @return array<T> Array ordenado
   */
  public static function ordenar($aItens, ...$xCriterios) {
    // Se for uma função, usa diretamente
    if (is_callable($xCriterios[0])) {
      usort($aItens, $xCriterios[0]);
      return $aItens;
    }

    // Caso contrário, processa os critérios
    usort($aItens, function($a, $b) use ($xCriterios) {
      foreach ($xCriterios as $xCriterio) {
        $sCampo = $xCriterio[0];
        $sDirecao = strtoupper($xCriterio[1] ?? 'ASC');
        
        $xValorA = self::obterValor($a, $sCampo);
        $xValorB = self::obterValor($b, $sCampo);
        
        $iComparacao = $xValorA <=> $xValorB;
        
        if ($iComparacao !== 0) {
          return $sDirecao === 'DESC' ? -$iComparacao : $iComparacao;
        }
      }
      return 0;
    });

    return $aItens;
  }

  /**
   * Indexa um array por uma chave específica
   *
   * @template T
   * @param array<T> $aItens Array de itens a serem indexados
   * @param string|callable(T): (string|int) $xChave Nome do campo ou função que retorna a chave
   * @return array<string|int, T> Array indexado
   */
  public static function indexar($aItens, $xChave) {
    $aResultado = [];
    
    foreach ($aItens as $xItem) {
      if (is_callable($xChave)) {
        $sIndice = $xChave($xItem);
      } else {
        $sIndice = self::obterValor($xItem, $xChave);
      }
      
      if ($sIndice !== null) {
        $aResultado[$sIndice] = $xItem;
      }
    }
    
    return $aResultado;
  }

  /**
   * Agrupa itens do array
   *
   * @template T
   * @param array<T> $aItens Array de itens a serem agrupados
   * @param string|callable(T): (string|int) $xChave Nome do campo ou função que retorna a chave de agrupamento
   * @param string|callable(T): (string|int)|null $xSubChave Chave secundária opcional para agrupamento aninhado
   * @return array<string|int, array<T>|array<string|int, array<T>>> Array agrupado
   */
  public static function agrupar($aItens, $xChave, $xSubChave = null) {
    $aResultado = [];
    
    foreach ($aItens as $xItem) {
      // Determina a chave principal
      if (is_callable($xChave)) {
        $sChavePrincipal = $xChave($xItem);
      } else {
        $sChavePrincipal = self::obterValor($xItem, $xChave);
      }
      
      if ($sChavePrincipal === null) {
        continue;
      }
      
      // Se não há subchave, agrupa diretamente
      if ($xSubChave === null) {
        if (!isset($aResultado[$sChavePrincipal])) {
          $aResultado[$sChavePrincipal] = [];
        }
        $aResultado[$sChavePrincipal][] = $xItem;
      } else {
        // Determina a subchave
        if (is_callable($xSubChave)) {
          $sSubChave = $xSubChave($xItem);
        } else {
          $sSubChave = self::obterValor($xItem, $xSubChave);
        }
        
        if ($sSubChave === null) {
          continue;
        }
        
        if (!isset($aResultado[$sChavePrincipal])) {
          $aResultado[$sChavePrincipal] = [];
        }
        if (!isset($aResultado[$sChavePrincipal][$sSubChave])) {
          $aResultado[$sChavePrincipal][$sSubChave] = [];
        }
        $aResultado[$sChavePrincipal][$sSubChave][] = $xItem;
      }
    }
    
    return $aResultado;
  }

  /**
   * Encontra o primeiro item que atende a condição
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param callable(T): bool|array $xCondicao Condição de busca
   * @return T|null Primeiro item encontrado ou null
   */
  public static function primeiro($aItens, $xCondicao) {
    foreach ($aItens as $xItem) {
      if (self::avaliarCondicao($xItem, $xCondicao)) {
        return $xItem;
      }
    }
    return null;
  }

  /**
   * Encontra o último item que atende a condição
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param callable(T): bool|array $xCondicao Condição de busca
   * @return T|null Último item encontrado ou null
   */
  public static function ultimo($aItens, $xCondicao) {
    $xUltimo = null;
    
    foreach ($aItens as $xItem) {
      if (self::avaliarCondicao($xItem, $xCondicao)) {
        $xUltimo = $xItem;
      }
    }
    
    return $xUltimo;
  }

  /**
   * Remove itens duplicados baseado em campo ou função
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string|callable(T): mixed $xChave Nome do campo ou função
   * @return array<T> Array sem duplicatas
   */
  public static function unicos($aItens, $xChave) {
    $aVistos = [];
    $aResultado = [];
    
    foreach ($aItens as $xItem) {
      if (is_callable($xChave)) {
        $xValor = $xChave($xItem);
      } else {
        $xValor = self::obterValor($xItem, $xChave);
      }
      
      $sChave = serialize($xValor);
      
      if (!isset($aVistos[$sChave])) {
        $aVistos[$sChave] = true;
        $aResultado[] = $xItem;
      }
    }
    
    return $aResultado;
  }

  /**
   * Verifica se todos os itens atendem a condição
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param callable(T): bool|array $xCondicao Condição a ser verificada
   * @return bool True se todos atendem, false caso contrário
   */
  public static function todos($aItens, $xCondicao) {
    foreach ($aItens as $xItem) {
      if (!self::avaliarCondicao($xItem, $xCondicao)) {
        return false;
      }
    }
    return true;
  }

  /**
   * Verifica se algum item atende a condição
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param callable(T)|array $xCondicao Condição a ser verificada
   * @return bool True se algum atende, false caso contrário
   */
  public static function algum($aItens, $xCondicao) {
    foreach ($aItens as $xItem) {
      if (self::avaliarCondicao($xItem, $xCondicao)) {
        return true;
      }
    }
    return false;
  }

  /**
   * Conta quantos itens atendem a condição
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param callable(T): bool|array $xCondicao Condição de contagem
   * @return int Quantidade de itens
   */
  public static function contar($aItens, $xCondicao) {
    $iContador = 0;
    
    foreach ($aItens as $xItem) {
      if (self::avaliarCondicao($xItem, $xCondicao)) {
        $iContador++;
      }
    }
    
    return $iContador;
  }

  /**
   * Verifica se o array contém um item específico
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param array $aItemBusca Item a ser buscado
   * @return bool True se contém, false caso contrário
   */
  public static function contem($aItens, $aItemBusca) {
    foreach ($aItens as $xItem) {
      $bIgual = true;
      foreach ($aItemBusca as $sChave => $xValor) {
        $xValorItem = self::obterValor($xItem, $sChave);
        if ($xValorItem === null || $xValorItem !== $xValor) {
          $bIgual = false;
          break;
        }
      }
      if ($bIgual) {
        return true;
      }
    }
    return false;
  }

  /**
   * Soma os valores de um campo específico
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string $sCampo Nome do campo a ser somado
   * @return float|int Soma dos valores
   */
  public static function somar($aItens, $sCampo) {
    $nSoma = 0;
    
    foreach ($aItens as $xItem) {
      $nSoma += self::obterValor($xItem, $sCampo) ?? 0;
    }
    
    return $nSoma;
  }

  /**
   * Calcula a média dos valores de um campo
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string $sCampo Nome do campo
   * @return float Média dos valores
   */
  public static function media($aItens, $sCampo) {
    if (empty($aItens)) {
      return 0;
    }
    
    return self::somar($aItens, $sCampo) / count($aItens);
  }

  /**
   * Encontra o valor máximo de um campo
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string $sCampo Nome do campo
   * @return mixed Valor máximo
   */
  public static function maximo($aItens, $sCampo) {
    if (empty($aItens)) {
      return null;
    }
    
    $xMaximo = null;
    
    foreach ($aItens as $xItem) {
      $xValor = self::obterValor($xItem, $sCampo);
      if ($xMaximo === null || $xValor > $xMaximo) {
        $xMaximo = $xValor;
      }
    }
    
    return $xMaximo;
  }

  /**
   * Encontra o valor mínimo de um campo
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string $sCampo Nome do campo
   * @return mixed Valor mínimo
   */
  public static function minimo($aItens, $sCampo) {
    if (empty($aItens)) {
      return null;
    }
    
    $xMinimo = null;
    
    foreach ($aItens as $xItem) {
      $xValor = self::obterValor($xItem, $sCampo);
      if ($xMinimo === null || $xValor < $xMinimo) {
        $xMinimo = $xValor;
      }
    }
    
    return $xMinimo;
  }

  /**
   * Agrupa e conta itens por campo
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string $sCampo Nome do campo para agrupamento
   * @return array<string|int, int> Array com contagem por valor
   */
  public static function contarPor($aItens, $sCampo) {
    $aResultado = [];
    
    foreach ($aItens as $xItem) {
      $xChave = self::obterValor($xItem, $sCampo);
      if ($xChave !== null) {
        if (!isset($aResultado[$xChave])) {
          $aResultado[$xChave] = 0;
        }
        $aResultado[$xChave]++;
      }
    }
    
    return $aResultado;
  }

  /**
   * Agrupa e soma valores de um campo
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string $sCampoGrupo Campo para agrupamento
   * @param string $sCampoSoma Campo a ser somado
   * @return array<string|int, float|int> Array com soma por grupo
   */
  public static function somarPor($aItens, $sCampoGrupo, $sCampoSoma) {
    $aResultado = [];
    
    foreach ($aItens as $xItem) {
      $xChave = self::obterValor($xItem, $sCampoGrupo);
      if ($xChave !== null) {
        if (!isset($aResultado[$xChave])) {
          $aResultado[$xChave] = 0;
        }
        $aResultado[$xChave] += self::obterValor($xItem, $sCampoSoma) ?? 0;
      }
    }
    
    return $aResultado;
  }

  /**
   * Converte array para formato chave-valor
   *
   * @template T
   * @param array<T> $aItens Array de itens
   * @param string $sCampoChave Campo que será a chave
   * @param string $sCampoValor Campo que será o valor
   * @return array<string|int, mixed> Array no formato chave => valor
   */
  public static function paraChaveValor($aItens, $sCampoChave, $sCampoValor) {
    $aResultado = [];
    
    foreach ($aItens as $xItem) {
      $xChave = self::obterValor($xItem, $sCampoChave);
      if ($xChave !== null) {
        $aResultado[$xChave] = self::obterValor($xItem, $sCampoValor);
      }
    }
    
    return $aResultado;
  }

  /**
   * Mapeia as chaves do array aplicando uma função de transformação
   *
   * @template T
   * @param array<string|int, T> $aItens Array de itens
   * @param callable(string|int, T): (string|int) $fTransformacao Função que recebe a chave atual e o item, retornando a nova chave
   * @return array<string|int, T> Array com as chaves transformadas
   */
  public static function mapearChaves($aItens, $fTransformacao) {
    $aResultado = [];
    
    foreach ($aItens as $sChave => $xItem) {
      $sNovaChave = $fTransformacao($sChave, $xItem);
      $aResultado[$sNovaChave] = $xItem;
    }
    
    return $aResultado;
  }

  /**
   * Avalia uma condição para um item
   *
   * @template T
   * @param T $xItem Item a ser avaliado
   * @param callable(T): bool|array $xCondicao Condição (função ou array [campo, operador, valor])
   * @return bool True se atende a condição, false caso contrário
   */
  private static function avaliarCondicao($xItem, $xCondicao) {
    // Se for uma função, executa diretamente
    if (is_callable($xCondicao)) {
      return $xCondicao($xItem);
    }
    
    // Se for array, processa a condição
    if (is_array($xCondicao)) {
      $sCampo = $xCondicao[0];
      
      // Formato: ['CAMPO', valor] - assume igualdade
      if (count($xCondicao) === 2) {
        return self::obterValor($xItem, $sCampo) == $xCondicao[1];
      }
      
      // Formato: ['CAMPO', 'OPERADOR', valor]
      $sOperador = strtoupper($xCondicao[1]);
      $xValorComparacao = $xCondicao[2];
      $xValorItem = self::obterValor($xItem, $sCampo);
      
      switch ($sOperador) {
        case '=':
        case '==':
          return $xValorItem == $xValorComparacao;
        
        case '===':
          return $xValorItem === $xValorComparacao;
        
        case '!=':
        case '<>':
          return $xValorItem != $xValorComparacao;
        
        case '!==':
          return $xValorItem !== $xValorComparacao;
        
        case '>':
          return $xValorItem > $xValorComparacao;
        
        case '>=':
          return $xValorItem >= $xValorComparacao;
        
        case '<':
          return $xValorItem < $xValorComparacao;
        
        case '<=':
          return $xValorItem <= $xValorComparacao;
        
        case 'IN':
          return is_array($xValorComparacao) && in_array($xValorItem, $xValorComparacao);
        
        case '!IN':
          return is_array($xValorComparacao) && !in_array($xValorItem, $xValorComparacao);
        
        case 'CONTAINS':
          return strpos($xValorItem, $xValorComparacao) !== false;
        
        case '!CONTAINS':
          return strpos($xValorItem, $xValorComparacao) === false;
        
        default:
          return false;
      }
    }
    
    return false;
  }

  /**
   * Obtém o valor de um campo de um item (array ou objeto)
   *
   * @param mixed $xItem Item (array ou objeto)
   * @param string $sCampo Nome do campo/propriedade
   * @return mixed Valor do campo ou null se não existir
   */
  private static function obterValor($xItem, $sCampo) {
    // Se for array
    if (is_array($xItem)) {
      return $xItem[$sCampo] ?? null;
    }
    
    // Se for objeto
    if (is_object($xItem) && property_exists($xItem, $sCampo)) {
      return $xItem->$sCampo;
    }
    
    return null;
  }
}

