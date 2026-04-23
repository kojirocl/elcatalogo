SELECT t.idTag, t.tag, 
COUNT(*) AS frecuencia FROM tag_perfil tp 
JOIN tags t ON tp.idTag = t.idTag
contrato
GROUP BY t.idTag, t.tag
ORDER BY frecuencia DESC
LIMIT 20;