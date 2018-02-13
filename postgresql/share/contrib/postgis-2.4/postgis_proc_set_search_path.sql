DO language plpgsql $$DECLARE param_postgis_schema text;
BEGIN
-- check if PostGIS is already installed
param_postgis_schema = (SELECT n.nspname from pg_extension e join pg_namespace n on e.extnamespace = n.oid WHERE extname = 'postgis');

-- if in middle install, it will be the current_schema or what was there already
param_postgis_schema = COALESCE(param_postgis_schema, current_schema());

IF param_postgis_schema != current_schema() THEN
	EXECUTE 'set search_path TO ' || quote_ident(param_postgis_schema);
END IF;

-- PostGIS set search path of functions

--
-- ALTER FUNCTION script
--

EXECUTE 'ALTER FUNCTION  _postgis_deprecate(oldname text, newname text, version text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Shift_Longitude(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_estimated_extent(text,text,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_estimated_extent(text,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_FindExtent(text,text,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_find_extent(text,text,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_FindExtent(text,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_find_extent(text,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_mem_size(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_3DLength_spheroid(geometry, spheroid ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_length_spheroid(geometry, spheroid ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_length2d_spheroid(geometry, spheroid ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_distance_spheroid(geom1 geometry, geom2 geometry,spheroid ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_point_inside_circle(geometry,float8,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_force_2d(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_force_3dz(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_force_3d(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_force_3dm(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_force_4d(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_force_collection(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_ForcePolygonCCW(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Affine(geometry,float8,float8,float8,float8,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Rotate(geometry,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Rotate(geometry,float8,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Rotate(geometry,float8,geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_RotateZ(geometry,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_RotateX(geometry,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_RotateY(geometry,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Translate(geometry,float8,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Translate(geometry,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Scale(geometry,float8,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Scale(geometry,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Transscale(geometry,float8,float8,float8,float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  populate_geometry_columns(use_typmod boolean  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  populate_geometry_columns(tbl_oid oid, use_typmod boolean  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  UpdateGeometrySRID(catalogn_name varchar,schema_name varchar,table_name varchar,column_name varchar,new_srid_in integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  UpdateGeometrySRID(varchar,varchar,varchar,integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  UpdateGeometrySRID(varchar,varchar,integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  find_srid(varchar,varchar,varchar ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  get_proj4_from_srid(integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Transform(geometry,integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Transform(geom geometry, to_proj text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Transform(geom geometry, from_proj text, to_proj text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Transform(geom geometry, from_proj text, to_srid integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  postgis_full_version( ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_SnapToGrid(geometry, float8, float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_SnapToGrid(geometry, float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_line_interpolate_point(geometry, float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_line_substring(geometry, float8, float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_line_locate_point(geom1 geometry, geom2 geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_locate_along_measure(geometry, float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(geometry,float8,integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(geometry,float8,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_IsValidReason(geometry, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_IsValid(geometry, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_GeomFromGML(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_GMLToSQL(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsGML(geom geometry, maxdecimaldigits int4 , options int4  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsKML(geom geometry, maxdecimaldigits int4  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  _ST_AsGeoJson(int4, geometry, int4, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsGeoJson(gj_version int4, geom geometry, maxdecimaldigits int4 , options int4  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_NumPatches(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PatchN(geometry, integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PointFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PointFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_LineFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_LineFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolyFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolyFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolygonFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolygonFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MLineFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MLineFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiLineStringFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiLineStringFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPointFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPointFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiPointFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPolyFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPolyFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiPolygonFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiPolygonFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_GeomCollFromText(text, int4 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_GeomCollFromText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_GeomFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PointFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PointFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_LineFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_LineFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_LinestringFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_LinestringFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolyFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolyFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolygonFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_PolygonFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPointFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPointFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiPointFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiPointFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiLineFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MLineFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MLineFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPolyFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MPolyFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiPolyFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MultiPolyFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_GeomCollFromWKB(bytea, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_GeomCollFromWKB(bytea ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_MaxDistance(geom1 geometry, geom2 geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_LongestLine(geom1 geometry, geom2 geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_BdPolyFromText(text, integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_BdMPolyFromText(text, integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  UnlockRows(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  LockRow(text, text, text, text, timestamp ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  LockRow(text, text, text, text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  LockRow(text, text, text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  LockRow(text, text, text, timestamp ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  AddAuth(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  CheckAuth(text, text, text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  EnableLongTransactions( ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  LongTransactionsEnabled( ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  DisableLongTransactions( ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsText(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  overlaps_geog(geography, gidx ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsSVG(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsGML(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsGML(geog geography, maxdecimaldigits int4 , options int4  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsKML(geog geography, maxdecimaldigits int4  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsKML(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsGeoJson(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsGeoJson(geog geography, maxdecimaldigits int4 , options int4  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsGeoJson(gj_version int4, geog geography, maxdecimaldigits int4 , options int4  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Distance(geography, geography, boolean ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Distance(geography, geography ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Distance(text, text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  _ST_DistanceUnCached(geography, geography, boolean ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  _ST_DistanceUnCached(geography, geography ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  _ST_DistanceTree(geography, geography ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Area(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Length(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  _ST_BestSRID(geography ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(geography, float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(geography, float8, integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(geography, float8, text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(text, float8 ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(text, float8, integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Buffer(text, float8, text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Intersection(geography, geography ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Intersection(text, text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsBinary(geography,text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_AsEWKT(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Centroid(text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_DistanceSphere(geom1 geometry, geom2 geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_distance_sphere(geom1 geometry, geom2 geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  postgis_type_name(geomname varchar, coord_dimension integer, use_new_name boolean  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  postgis_constraint_srid(geomschema text, geomtable text, geomcolumn text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  postgis_constraint_dims(geomschema text, geomtable text, geomcolumn text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  postgis_constraint_type(geomschema text, geomtable text, geomcolumn text ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_CurveToLine(geometry, integer ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_CurveToLine(geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_Polygon(geometry, int ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  contains_2d(geometry, box2df ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  is_contained_2d(geometry, box2df ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  overlaps_2d(geometry, box2df ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  overlaps_nd(geometry, gidx ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  _st_concavehull(param_inputgeom geometry ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
EXECUTE 'ALTER FUNCTION  ST_ConcaveHull(param_geom geometry, param_pctconvex float, param_allow_holes boolean  ) SET search_path=' || quote_ident(param_postgis_schema) || ',pg_catalog;';
END;$$;